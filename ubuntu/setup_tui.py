#!/usr/bin/env python3
"""Local terminal UI; standard library only. No credentials enter command arguments."""
import argparse
import json
import os
from pathlib import Path
import re
import shutil
import subprocess
import sys
import tempfile
import unicodedata


class Settings:
    def __init__(self, root):
        self.root = Path(root)
        self.env_file = self.root / '.env'
        self.pages_file = self.root / '.idcc-pages.json'
        self.catalog = sorted(json.loads((self.root / 'common/Config/pages.json').read_text(encoding='utf-8-sig')),
                              key=lambda p: (p['group'], p['label']))
        ids = [p['id'] for p in self.catalog]
        if len(ids) != len(set(ids)):
            raise ValueError('頁面目錄包含重複 ID。')

    def read_env(self, path=None):
        path = Path(path) if path is not None else self.env_file
        result = {}
        if not path.exists():
            return result
        for line in path.read_text(encoding='utf-8-sig').splitlines():
            match = re.match(r'^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=\s*(.*)$', line)
            if not match:
                continue
            key, value = match.groups()
            value = value.strip()
            if len(value) >= 2 and value[0] == value[-1] == "'":
                value = value[1:-1].replace("\\'", "'")
            elif len(value) >= 2 and value[0] == value[-1] == '"':
                value = re.sub(r'\\([\\"])', r'\1', value[1:-1]).replace('$$', '$')
            else:
                value = re.split(r'\s+#', value, maxsplit=1)[0].strip()
            result[key] = value
        return result

    @staticmethod
    def encode(value):
        value = str(value)
        if any(c in value for c in '\r\n\0'):
            raise ValueError('設定值不能包含換行或空字元。')
        return '"' + value.replace('\\', '\\\\').replace('"', '\\"').replace('$', '$$') + '"'

    @staticmethod
    def atomic_write(path, content):
        fd, tmp = tempfile.mkstemp(prefix=path.name + '.', dir=str(path.parent))
        try:
            with os.fdopen(fd, 'w', encoding='utf-8', newline='\n') as stream:
                stream.write(content)
                stream.flush()
                os.fsync(stream.fileno())
            os.replace(tmp, path)
        finally:
            if os.path.exists(tmp):
                os.unlink(tmp)

    def save_env(self, values, path=None, remove=()):
        target = Path(path) if path is not None else self.env_file
        encoded = {k: self.encode(v) for k, v in values.items()}
        original = target.read_text(encoding='utf-8-sig').splitlines() if target.exists() else []
        lines, seen = [], set()
        for line in original:
            match = re.match(r'^\s*([A-Za-z_][A-Za-z0-9_]*)\s*=', line)
            key = match[1] if match else None
            if key in remove:
                continue
            if key in encoded:
                if key not in seen:
                    lines.append(key + '=' + encoded[key])
                    seen.add(key)
            else:
                lines.append(line)
        lines += [key + '=' + value for key, value in encoded.items() if key not in seen]
        self.atomic_write(target, '\n'.join(lines) + '\n')

    def connection_profiles(self):
        values = self.read_env()
        pg = self.read_env(self.root / '.env.postgres')
        legacy = (not any(k.startswith('DEMOIDCC_DB_') for k in values)
                  and values.get('IDCC_CONNECTION_LAYOUT') != '2'
                  and values.get('IDCC_DB_DRIVER') != 'pgsql')
        database = (pg.get('IDCC_DB_NAME') or pg.get('POSTGRES_DB')
                    or ('idcc' if pg.get('POSTGRES_PASSWORD') else 'BatchDyeingCentral'))
        prefix = 'IDCC' if legacy else 'DEMOIDCC'
        suffixes = ('HOST', 'NAME', 'UID', 'PWD', 'TRUST_SERVER_CERTIFICATE')
        return {
            'IDCC': dict(HOST='postgres', PORT='5432', NAME=database, UID='idcc', PWD=pg.get('POSTGRES_PASSWORD', ''), DRIVER='pgsql'),
            'DemoIDCC': {k: values.get(prefix + '_DB_' + k, '') for k in suffixes},
            'SPC': {k: values.get('SPC_DB_' + k, '') for k in suffixes},
            'legacy': legacy,
        }

    def prepare_connections(self, use_installed_database=False):
        profiles = self.connection_profiles()
        if use_installed_database:
            profiles['IDCC']['NAME'] = 'BatchDyeingCentral'
        values = self.read_env()
        updates = {'IDCC_CONNECTION_LAYOUT': '2'}
        if not values.get('IDCC_DEMO_MODE'):
            updates['IDCC_DEMO_MODE'] = 'true'
        for key, value in profiles['DemoIDCC'].items():
            if 'DEMOIDCC_DB_' + key not in values:
                updates['DEMOIDCC_DB_' + key] = value
        remove = ['IDCC_DB_' + key for key in ('HOST', 'PORT', 'NAME', 'UID', 'PWD', 'DRIVER', 'TRUST_SERVER_CERTIFICATE')]
        self.save_env(updates, remove=remove)
        if profiles['IDCC']['PWD']:
            primary = {'IDCC_DB_' + key: value for key, value in profiles['IDCC'].items()}
            primary['POSTGRES_DB'] = profiles['IDCC']['NAME']
            self.save_env(primary, path=self.root / '.env.postgres')


    def enabled(self):
        known = {p['id'] for p in self.catalog}
        if not self.pages_file.exists():
            return known
        data = json.loads(self.pages_file.read_text(encoding='utf-8-sig'))
        if not isinstance(data, dict) or data.get('version') != 1 or not isinstance(data.get('enabled'), list):
            raise ValueError('頁面設定格式不正確，請重新選擇並儲存。')
        if any(not isinstance(i, str) or i not in known for i in data['enabled']):
            raise ValueError('頁面設定包含未知 ID。')
        return set(data['enabled'])

    def toggle(self, enabled, page):
        linked = {p['id'] for p in self.catalog if p['prefix'] and p['prefix'] == page['prefix']} or {page['id']}
        return enabled - linked if page['id'] in enabled else enabled | linked

    def save_pages(self, enabled):
        known = {p['id'] for p in self.catalog}
        if not set(enabled) <= known:
            raise ValueError('包含未知頁面。')
        # The same physical module must not be partly enabled through another menu entry.
        enabled = set(enabled)
        for page in self.catalog:
            if page['id'] in enabled and page['prefix']:
                enabled.update(p['id'] for p in self.catalog if p['prefix'] == page['prefix'])
        self.atomic_write(self.pages_file, json.dumps({'version': 1, 'enabled': sorted(enabled)}, ensure_ascii=False, indent=2) + '\n')

    @staticmethod
    def validate_database(values):
        for slot in ('DEMOIDCC', 'SPC'):
            present = sum(bool(values.get(slot + '_DB_' + key)) for key in ('HOST', 'NAME', 'UID', 'PWD'))
            if present not in (0, 4):
                raise ValueError(slot + ' 請填完整四個欄位，或全部留空稍後設定。')


def docker_command(root):
    binary = shutil.which('docker')
    if not binary:
        raise ValueError('找不到 Docker，請先從主選單安裝環境。')
    endpoint = os.environ.get('DOCKER_HOST', 'unix:///var/run/docker.sock')
    if not endpoint.startswith('unix:///'):
        raise ValueError('只允許本機 Unix Docker socket，請移除遠端 DOCKER_HOST。')
    env = dict(os.environ)
    env.pop('DOCKER_HOST', None)
    env.pop('DOCKER_CONTEXT', None)
    values = Settings(root).read_env()
    env['IDCC_HTTP_PORT'] = values.get('IDCC_HTTP_PORT', '8080')
    owner = root.stat()
    env['IDCC_APACHE_USER'] = '#' + str(owner.st_uid) if owner.st_uid > 0 and owner.st_gid > 0 else 'www-data'
    env['IDCC_APACHE_GROUP'] = '#' + str(owner.st_gid) if owner.st_uid > 0 and owner.st_gid > 0 else 'www-data'
    command = [binary, '--host', endpoint]
    if subprocess.run(command + ['info'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, env=env).returncode:
        if os.geteuid() != 0 and shutil.which('sudo'):
            command = ['sudo', '--', 'env', '-u', 'DOCKER_HOST', '-u', 'DOCKER_CONTEXT',
                       'IDCC_HTTP_PORT=' + env['IDCC_HTTP_PORT'], 'IDCC_APACHE_USER=' + env['IDCC_APACHE_USER'],
                       'IDCC_APACHE_GROUP=' + env['IDCC_APACHE_GROUP']] + command
        if subprocess.run(command + ['info'], stdout=subprocess.DEVNULL, stderr=subprocess.DEVNULL, env=env).returncode:
            raise ValueError('Docker 尚未啟動或無存取權限，請先執行安裝／啟動環境。')
    return command, env


def clip(text, width):
    out, used = '', 0
    for char in str(text):
        size = 2 if unicodedata.east_asian_width(char) in ('W', 'F') else 1
        if used + size > width:
            break
        out += char
        used += size
    return out


class TerminalUI:
    def __init__(self, screen, curses, root):
        self.screen, self.c = screen, curses
        self.settings = Settings(root)
        self.root = Path(root)
        self.status = ''
        screen.keypad(True)
        try:
            curses.curs_set(0)
        except curses.error:
            pass

    def draw(self, title, items, selected=0, footer='↑↓ 選擇，Enter 確定，q 返回'):
        self.screen.erase()
        h, w = self.screen.getmaxyx()
        if h < 20 or w < 70:
            self.screen.addstr(0, 0, clip('請將終端機放大至至少 70 欄 × 20 列；q 可返回。', max(1, w - 1)))
            self.screen.refresh()
            return False
        self.screen.addstr(0, 1, clip('IDCC DemoBeta ｜ ' + title, w - 3), self.c.A_BOLD)
        self.screen.addstr(1, 1, clip(str(self.root), w - 3))
        count = h - 7
        start = max(0, min(selected - count + 1, len(items) - count))
        for offset, item in enumerate(items[start:start + count]):
            index = start + offset
            self.screen.addstr(offset + 3, 2, clip(item, w - 5), self.c.A_REVERSE if index == selected else self.c.A_NORMAL)
        self.screen.addstr(h - 3, 1, clip(self.status, w - 3))
        self.screen.addstr(h - 2, 1, clip(footer, w - 3))
        self.screen.refresh()
        return True

    def choose(self, title, items, selected=0):
        while True:
            ready = self.draw(title, items, selected)
            key = self.screen.get_wch()
            if key in ('q', 'Q', '\x1b'):
                return None
            if not ready:
                continue
            if key == self.c.KEY_UP:
                selected = (selected - 1) % len(items)
            elif key == self.c.KEY_DOWN:
                selected = (selected + 1) % len(items)
            elif key in ('\n', '\r', self.c.KEY_ENTER):
                return selected

    def edit(self, label, value='', password=False):
        original = value
        while True:
            shown = '*' * len(value) if password else value
            ready = self.draw(label, [shown or '（空白）'], 0, '直接編輯；Ctrl+U 清空；Enter 儲存輸入；Esc 取消')
            key = self.screen.get_wch()
            if key == '\x1b':
                return original
            if not ready:
                continue
            if key in ('\n', '\r', self.c.KEY_ENTER):
                return value
            if key == '\x15':
                value = ''
            elif key in ('\b', '\x7f', self.c.KEY_BACKSPACE):
                value = value[:-1]
            elif isinstance(key, str) and key.isprintable():
                value += key

    def confirm(self, message):
        return self.choose(message, ['取消', '確定繼續']) == 1

    def external(self, action):
        self.c.def_prog_mode()
        self.c.endwin()
        try:
            code = action()
            print('\n操作完成。' if code == 0 else '\n操作未完成，請查看上方訊息；若要求重開機，重開後再次執行 setup.sh。')
        except (OSError, ValueError) as error:
            print('\n錯誤：' + str(error))
        except KeyboardInterrupt:
            print('\n操作中斷。')
        finally:
            try:
                input('\n按 Enter 返回設定選單…')
            except (EOFError, KeyboardInterrupt):
                pass
            self.c.reset_prog_mode()
            self.screen.clear()
            self.screen.refresh()

    def compose(self, arguments):
        def action():
            command, env = docker_command(self.root)
            return subprocess.run(command + ['compose', '--project-directory', str(self.root), '--file', str(self.root / 'compose.yaml')] + arguments,
                                  cwd=self.root, env=env).returncode
        self.external(action)

    def install(self):
        port = self.edit('網站連接埠（1024–65535）', self.settings.read_env().get('IDCC_HTTP_PORT', '8080'))
        if not port.isascii() or not port.isdecimal() or not 1024 <= int(port) <= 65535:
            self.status = '連接埠格式不正確。'
            return
        self.settings.save_env({'IDCC_HTTP_PORT': port})
        self.external(lambda: subprocess.run(['bash', str(self.root / 'ubuntu/install.sh'), '--port', port], cwd=self.root).returncode)

    def database(self):
        values = self.settings.read_env()
        profiles = self.settings.connection_profiles()
        for suffix, value in profiles['DemoIDCC'].items():
            values.setdefault('DEMOIDCC_DB_' + suffix, value)
        # Same two SQL Server slots and settings file as the Windows setup.
        demo = values.get('IDCC_DEMO_MODE', 'true').lower() not in ('false', '0', 'off', 'no')
        fields = [(slot + '_DB_' + suffix, ('DemoIDCC' if slot == 'DEMOIDCC' else slot) + ' ' + label, suffix == 'PWD')
                  for slot in ('DEMOIDCC', 'SPC') for suffix, label in (('HOST', '主機/IP'), ('NAME', '資料庫名稱'), ('UID', '登入帳號'), ('PWD', '密碼'))]
        checks = [(slot + '_DB_TRUST_SERVER_CERTIFICATE', ('DemoIDCC' if slot == 'DEMOIDCC' else slot) + ' 信任伺服器憑證（僅受信任測試環境）') for slot in ('DEMOIDCC', 'SPC')]
        selected = 0
        while True:
            entries = ['[x] Demo：IDCC 使用 DemoIDCC；SPC 獨立' if demo else '[ ] Demo 關閉：IDCC 使用 PostgreSQL；SPC 獨立']
            entries += ['IDCC Server（PostgreSQL，自動填入，僅供查看）']
            entries += [label + '：' + ('已設定（隱藏）' if password and values.get(key) else values.get(key, '（未設定）')) for key, label, password in fields]
            entries += [('[' + ('x' if values.get(key, 'false').lower() in ('true', '1', 'yes', 'on') else ' ') + '] ' + label) for key, label in checks]
            entries += ['只儲存設定', '儲存並套用到容器', '測試已儲存並套用的連線', '返回（放棄未儲存輸入）']
            choice = self.choose('資料庫設定（可全部留空稍後再填）', entries, selected)
            if choice is None or choice == len(entries) - 1:
                return
            selected = choice
            if choice == 0:
                if demo and not self.confirm('關閉 Demo 前須先移植 SQL 與資料表，確定？'):
                    continue
                demo = not demo
            elif choice == 1:
                primary = self.settings.connection_profiles()['IDCC']
                self.choose('IDCC Server（安裝後自動填入，不修改）', ['主機：' + primary['HOST'], '連接埠：' + primary['PORT'], '資料庫：' + primary['NAME'], '帳號：' + primary['UID'], '密碼：' + ('已自動填入（隱藏）' if primary['PWD'] else '安裝後自動產生'), '返回'])
            elif 2 <= choice <= len(fields) + 1:
                key, label, secret = fields[choice - 2]
                values[key] = self.edit(label, values.get(key, ''), password=secret)
                if not secret:
                    values[key] = values[key].strip()
            elif choice <= len(fields) + len(checks) + 1:
                key = checks[choice - len(fields) - 2][0]
                values[key] = 'false' if values.get(key, 'false').lower() in ('true', '1', 'yes', 'on') else 'true'
            elif choice == len(entries) - 2:
                self.compose(['exec', '-T', 'web', 'php', 'docker/diagnose-setup.php', '--human'])
            else:
                try:
                    updates = {key: values.get(key, '') for key, _, _ in fields}
                    updates.update({key: values.get(key, 'false') for key, _ in checks})
                    updates['IDCC_DEMO_MODE'] = str(demo).lower()
                    self.settings.validate_database(updates)
                    self.settings.save_env(updates)
                    self.settings.prepare_connections()
                    self.status = '已儲存。只儲存不會更改執行中的容器。'
                    if choice == len(entries) - 3:
                        self.compose(['up', '-d', '--force-recreate', '--wait', '--wait-timeout', '180'])
                except (ValueError, OSError) as error:
                    self.status = str(error)

    def pages(self):
        try:
            enabled = self.settings.enabled()
        except (ValueError, OSError) as error:
            if not self.confirm('設定無法讀取，重新選擇？原檔在儲存前保留。'):
                return
            self.status = str(error)
            enabled = set()
        selected = 0
        catalog = self.settings.catalog
        while True:
            items = [('[' + ('x' if p['id'] in enabled else ' ') + '] [' + p['group'] + '] ' + p['label']) for p in catalog]
            ready = self.draw('頁面選擇（共用目錄連動）', items, selected, '↑↓移動  空白勾選  a全選  n全取消  s儲存  q取消返回')
            key = self.screen.get_wch()
            if key in ('q', 'Q', '\x1b'):
                return
            if not ready:
                continue
            if key == self.c.KEY_UP:
                selected = (selected - 1) % len(catalog)
            elif key == self.c.KEY_DOWN:
                selected = (selected + 1) % len(catalog)
            elif key == self.c.KEY_NPAGE:
                selected = min(len(catalog) - 1, selected + 10)
            elif key == self.c.KEY_PPAGE:
                selected = max(0, selected - 10)
            elif key == ' ':
                enabled = self.settings.toggle(enabled, catalog[selected])
            elif key in ('a', 'A'):
                enabled = {p['id'] for p in catalog}
            elif key in ('n', 'N'):
                enabled = set()
            elif key in ('s', 'S'):
                self.settings.save_pages(enabled)
                self.status = '頁面已儲存，瀏覽器按 Ctrl+F5。首次更新需先重建映像。'
                return

    def main(self, initial='main'):
        if initial == 'database':
            self.database()
            return
        if initial == 'pages':
            self.pages()
            return
        actions = [self.install, self.database, self.pages,
                   lambda: self.compose(['exec', '-T', 'web', 'php', 'docker/diagnose-setup.php', '--human']),
                   lambda: self.external(lambda: subprocess.run(['bash', str(self.root / 'ubuntu/reset.sh')], cwd=self.root).returncode)]
        while True:
            choice = self.choose('主選單', ['安裝／啟動環境（可略過資料庫）', '獨立資料庫設定', '勾選要保留的頁面', '診斷目前連線', '重設環境（保留資料，需輸入 RESET）', '離開'])
            if choice is None or choice == 5:
                return
            try:
                actions[choice]()
            except (ValueError, OSError) as error:
                self.status = str(error)


def main():
    parser = argparse.ArgumentParser(description='IDCC Ubuntu 終端機安裝與設定')
    parser.add_argument('--screen', choices=['main', 'database', 'pages'], default='main')
    parser.add_argument('--check', action='store_true', help='只檢查頁面設定，不啟動互動介面、不安裝套件')
    parser.add_argument('--installed-database', action='store_true', help='Use BatchDyeingCentral after installation ensures it exists')
    parser.add_argument('--prepare-connections', action='store_true', help='Initialize connection profiles without showing credentials')
    args = parser.parse_args()
    root = Path(__file__).resolve().parent.parent
    try:
        if args.prepare_connections:
            Settings(root).prepare_connections(use_installed_database=args.installed_database)
            print('Connection profiles prepared. SPC settings preserved.')
            return 0
        if args.check:
            settings = Settings(root)
            print(json.dumps({'ok': True, 'catalog_pages': len(settings.catalog), 'enabled_pages': len(settings.enabled()), 'env_exists': settings.env_file.exists()}))
            return 0
        if not sys.stdin.isatty() or not sys.stdout.isatty():
            raise ValueError('請在互動式終端機執行；SSH 請使用 ssh -t。')
        import curses
        import locale
        locale.setlocale(locale.LC_ALL, '')
        curses.wrapper(lambda screen: TerminalUI(screen, curses, root).main(args.screen))
        return 0
    except (ValueError, OSError, ImportError) as error:
        print('設定工具：' + str(error), file=sys.stderr)
        return 1
    except KeyboardInterrupt:
        return 130


if __name__ == '__main__':
    sys.exit(main())
