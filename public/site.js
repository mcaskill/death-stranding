(function () {
    window.document.documentElement.classList.add('has-js');

    /**
     * Detects whether a web storage API is both supported and available.
     *
     * @link     https://developer.mozilla.org/en-US/docs/Web/API/Web_Storage_API/Using_the_Web_Storage_API#Feature-detecting_localStorage
     * @constant {boolean} Returns TRUE if the `localStorage` API is supported and available.
     */
    const isStorageAvailable = (function () {
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

    /**
     * An event listener object to toggle a range of checkboxes using the shift-key.
     *
     * @typedef {Object} ToggleCheckedInputRangeEventListener
     *
     * @implements {EventListener}
     *
     * @property {HTMLInputElement} lastInput               - The previously checked, or unchecked, input.
     * @property {HTMLElement}      commonAncestorContainer - The common ancestor that contains the input collection.
     * @property {Function}         querySelectorAll        - The default input collection handler.
     * @property {Function}         handleEvent             - The default event listener.
     */

    /**
     * Creates an event listener object to toggle a range of checkboxes using the shift-key.
     *
     * @param  {Function} [querySelectorAllFn] - A proxy event handler function.
     *
     * @return {ToggleCheckedInputRangeEventListener}
     */
    const createEventListenerToToggleCheckedInputRange = function (querySelectorAllFn) {
        const commonAncestorContainerSelector = [
            'ul',
            'ol',
            'dl',
            'fieldset',
            'form',
            'table',
            'details',
            'aside',
            'footer',
            'header',
            'section',
            'main',
        ].join(',');

        return {
            lastInput: null,
            commonAncestorContainer: null,
            querySelectorAll: querySelectorAllFn || function () {
                this.commonAncestorContainer.querySelectorAll('input[type="checkbox"]');
            },
            handleEvent: function (event) {
                let currInput = event.target;
                if (currInput.type !== 'checkbox') {
                    return;
                }

                if (event.shiftKey && this.lastInput !== null && this.lastInput !== currInput) {
                    let inputs, inBetween, options;

                    if (!this.commonAncestorContainer) {
                        this.commonAncestorContainer = currInput.closest(commonAncestorContainerSelector);
                    }

                    inputs  = this.querySelectorAll();
                    options = {
                        bubbles: true,
                        cancelable: true
                    };

                    this.lastInput.checked = currInput.checked;
                    this.lastInput.dispatchEvent(new Event('change', options));

                    inBetween = false;
                    inputs.forEach((input) => {
                        if (input === this.lastInput || input === currInput) {
                            inBetween = !inBetween;
                        } else if (inBetween) {
                            input.checked = currInput.checked;
                            input.dispatchEvent(new Event('change', options));
                        }
                    });
                }

                this.lastInput = currInput;
            }
        };
    };

    /**
     * An event listener object to persist input checked state in web storage.
     *
     * @typedef {Object} PersistCheckedInputEventListener
     *
     * @implements {EventListener}
     *
     * @property {Storage}  storageArea   - The web storage object.
     * @property {Function} getStoreKey   - The default storage key resolver.
     * @property {Function} getStoreValue - The default storage value resolver.
     * @property {Function} handleEvent   - The default event listener.
     * @property {Function} updateStore   - The web storage object updater.
     */

    /**
     * Creates an event listener object to persist input checked state in web storage.
     *
     * @param  {Storage}  store     - The web storage object.
     * @param  {Function} [keyFn]   - A storage key resolver.
     * @param  {Function} [valFn]   - A storage value resolver.
     * @param  {Function} [proxyFn] - A proxy event handler function.
     *
     * @return {PersistCheckedInputEventListener}
     */
    const createEventListenerToPersistCheckedInput = function (store, keyFn, valFn, proxyFn) {
        return {
            storageArea: store,
            getStoreKey: keyFn || function (input, event) {
                return `${input.name}_${input.value}`;
            },
            getStoreValue: valFn || function (input, event) {
                return 1;
            },
            handleEvent: proxyFn || function (event) {
                this.updateStore(event.target, event);
            },
            updateStore: function (input, event) {
                let key = this.getStoreKey(input, event);

                if (input.checked) {
                    this.storageArea.setItem(key, this.getStoreValue(input, event));
                } else {
                    this.storageArea.removeItem(key);
                }
            }
        };
    }

    /**
     * Catalogue Tracker
     */
    const Track = (function () {
        const storePrefix = 'ds_order_';
        const checklistRangeQueryCallback = function () {
            let selector = 'input[type="checkbox"]';

            return this.commonAncestorContainer.querySelectorAll(selector);
        };
        const persistCheckedInputEventCallback = function (event) {
            let input = event.target;
            this.updateStore(input, event);

            let row = input.closest('tr');
            if (row) {
                row.classList.toggle('tr-todo', !input.checked);
                row.classList.toggle('tr-done', input.checked);
            }
        };
        const persistCheckedInputEventArgs = [
            (input) => storePrefix + input.value,
            () => new Date(),
            persistCheckedInputEventCallback
        ];

        return {
            /**
             * Initialize the given tables.
             *
             * @param  {HTMLTableElement[]} tables - One or more table elements.
             * @param  {Storage}            store  - A web storage object.
             * @return {void}
             */
            init: function (tables, store) {
                if (tables instanceof Element) {
                    tables = [ tables ];
                }

                for (const table of tables) {
                    for (const tbody of table.tBodies) {
                        for (const row of tbody.rows) {
                            try {
                                row.input = document.getElementById('cb-select-' + row.dataset.orderId);
                                if (row.input) {
                                    row.input.checked = store.getItem(storePrefix + row.input.value);

                                    row.classList.toggle('tr-todo', !row.input.checked);
                                    row.classList.toggle('tr-done', row.input.checked);
                                }
                            } catch (error) {
                                console.error('[Bridges]', '[Table Body Row: ' + row.rowIndex + ']', error);
                            }
                        }
                    }

                    table.eventset = table.eventset || {};

                    table.eventset.click = createEventListenerToToggleCheckedInputRange(checklistRangeQueryCallback);
                    table.addEventListener('click', table.eventset.click, { passive: true });

                    table.eventset.change = createEventListenerToPersistCheckedInput(...[ store, ...persistCheckedInputEventArgs ]);
                    table.addEventListener('change', table.eventset.change, { passive: true });

                    table.classList.add('is-trackable');
                }
            },
            /**
             * Uninitialize the given tables.
             *
             * @param  {HTMLTableElement[]} tables - One or more table elements.
             * @return {void}
             */
            unload: function (tables) {
                if (tables instanceof Element) {
                    tables = [ tables ];
                }

                for (const table of tables) {
                    if (table.eventset.click) {
                        table.removeEventListener('click', table.eventset.click);
                    }

                    if (table.eventset.change) {
                        table.removeEventListener('change', table.eventset.change);
                    }

                    table.classList.remove('is-trackable');
                }
            }
        };
    })();

    window.addEventListener('load', function () {
        const tables = window.document.querySelectorAll('table.ds-orders');

        try {
            if (isStorageAvailable) {
                Track.init(tables, window.localStorage);
            }
        } catch (error) {
            console.error('[Bridges]', error);
        }
    });
})();
