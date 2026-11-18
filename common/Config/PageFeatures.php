<?php
function idccPageCatalog(): array {
    static $catalog;
    return $catalog ??= json_decode(file_get_contents(__DIR__ . '/pages.json'), true, 512, JSON_THROW_ON_ERROR);
}
function idccEnabledPages(): array {
    static $enabled;
    if ($enabled !== null) return $enabled;
    $file = dirname(__DIR__, 2) . '/.idcc-pages.json';
    if (!is_file($file)) return $enabled = array_column(idccPageCatalog(), 'id');
    $data = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
    if (($data['version'] ?? null) !== 1 || !is_array($data['enabled'] ?? null)) throw new RuntimeException('Invalid page selection configuration.');
    $known = array_column(idccPageCatalog(), 'id');
    foreach ($data['enabled'] as $id) if (!is_string($id) || !in_array($id, $known, true)) throw new RuntimeException('Unknown page selection.');
    return $enabled = $data['enabled'];
}
function idccHasNavigation($node): bool {
    if (!is_array($node)) return false;
    if (isset($node['ajaxHash'])) return true;
    foreach ($node as $child) if (is_array($child) && idccHasNavigation($child)) return true;
    return false;
}
function idccFilterSidebar($node) {
    if (!is_array($node)) return $node;
    if (array_is_list($node)) {
        $out = [];
        foreach ($node as $child) { $item = idccFilterSidebar($child); if ($item !== null) $out[] = $item; }
        return $out;
    }
    if (isset($node['ajaxHash']) && !in_array($node['ajaxHash'], idccEnabledPages(), true)) return null;
    $hadNavigation = idccHasNavigation($node);
    foreach ($node as $key => $value) if (is_array($value)) $node[$key] = idccFilterSidebar($value);
    if (($node['element'] ?? '') === 'li' && $hadNavigation && !idccHasNavigation($node)) return null;
    return $node;
}
function idccPathAllowed(string $relative): bool {
    $matches = array_filter(idccPageCatalog(), static fn($p) => $p['prefix'] !== '' && str_starts_with($relative, $p['prefix']));
    if (!$matches) return true; // Shared framework, homepage and non-menu modules stay available.
    foreach ($matches as $page) if (in_array($page['id'], idccEnabledPages(), true)) return true;
    return false;
}
