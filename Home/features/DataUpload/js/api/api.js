/**
 * Fetches data from a given URL and returns a specified property from the JSON response.
 *
 * @param {string} url - The URL to fetch data from.
 * @param {string} property - The property to extract from the JSON response.
 * @returns {Promise<any>} - A promise that resolves to the specified property from the JSON response.
 * @throws {Error} - Throws an error if the fetch fails or if the response is not ok.
 */
export async function fetchConfigProperty(url, property) {
    try {
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(`Network response was not ok: ${response.statusText}`);
        }
        const config = await response.json();
        return config[property];
    } catch (error) {
        console.error(`Error fetching ${property}:`, error);
        throw error;
    }
}

/**
 * Retrieves module paths from the configuration.
 *
 * @returns {Promise<any>} - A promise that resolves to the module paths from the configuration.
 * @throws {Error} - Throws an error if the fetch fails or if the response is not ok.
 */
export async function getUtilModulePaths() {
    return fetchConfigProperty("../env/env.json", "modulePaths");
}

/**
 * Retrieves router paths from a local router configuration file.
 *
 * @param {string} localRouterPath - The local path to the router configuration file.
 * @returns {Promise<any>} - A promise that resolves to the router paths from the local configuration.
 * @throws {Error} - Throws an error if the fetch fails or if the response is not ok.
 */
export async function getRouterPaths(localRouterPath) {
    return fetchConfigProperty(`${localRouterPath}`, "localPaths");
}
