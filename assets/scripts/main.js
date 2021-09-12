/**
 * @file Bootstrap
 */

import Track from './modules/track.js';
import Search from './modules/search.js';

window.document.documentElement.classList.add('has-js');

/**
 * Detects whether a web storage API is both supported and available.
 *
 * @link     https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API#Feature-detecting_localStorage
 * @constant {boolean} Returns TRUE if the `localStorage` API is supported and available.
 */
export const isStorageAvailable = (function () {
    let storage;

    try {
        storage = window.localStorage;
        const x = '__storage_test__';
        storage.setItem(x, x);
        storage.removeItem(x);
        return true;
    } catch (error) {
        return error instanceof DOMException && (
            // everything except Firefox
            error.code === 22 ||
            // Firefox
            error.code === 1014 ||
            // test name field too, because code might not be present
            // everything except Firefox
            error.name === 'QuotaExceededError' ||
            // Firefox
            error.name === 'NS_ERROR_DOM_QUOTA_REACHED') &&
            // acknowledge QuotaExceededError only if there's something already stored
            (storage && storage.length !== 0);
    }
})();

window.Bridges = {
    find:        Search.find,
    filter:      Search.filter,
    clearFilter: Search.clear,
};

window.addEventListener('load', () => {
    const status = window.document.getElementById('ds-status');
    const form   = window.document.querySelector('form.js-search');
    const tables = window.document.querySelectorAll('table.js-orders');

    try {
        if (isStorageAvailable) {
            const track  = new Track(tables, window.localStorage);
            const search = new Search(tables, form, status);

            window.Bridges.find        = search.find;
            window.Bridges.filter      = search.filter;
            window.Bridges.clearFilter = search.clear;
            window.Bridges.destroy     = () => {
                search.destroy();
                track.destroy();
            };
        }
    } catch (error) {
        console.error('[Bridges]', error);
    }
});
