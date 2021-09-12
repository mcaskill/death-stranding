/**
 * @file Orders Tracker
 */

const STORE_PREFIX = 'ds_order_';

/**
 * Returns a list of input checkbox elements
 * or a subset if searching the orders.
 *
 * @this {ToggleCheckedInputRange~EventListener}
 *
 * @return {HTMLInputElement[]|NodeList}
 */
const checklistRangeQueryCallback = function () {
    let selector = 'input[type="checkbox"]';

    if (this.commonAncestorContainer.classList.contains('is-searching')) {
        selector = 'tr.is-match ' + selector;
    }

    return this.commonAncestorContainer.querySelectorAll(selector);
};

/**
 * Returns a list of input checkbox elements
 * or a subset if searching the orders.
 *
 * @this {PersistCheckedInput~EventListener}
 *
 * @return {void}
 */
const persistCheckedInputEventCallback = function (event) {
    let input = event.target;
    this.updateStore(input, event);

    let row = input.closest('tr');
    if (row) {
        row.classList.toggle('c-tr-todo', !input.checked);
        row.classList.toggle('c-tr-done', input.checked);
    }
};

/**
 * @var {[ PersistCheckedInput~KeyResolverCallback, PersistCheckedInput~ValueResolverCallback, PersistCheckedInput~HandlerEventCallback ]}
 */
const persistCheckedInputEventArgs = [
    (input) => STORE_PREFIX + input.value,
    () => new Date(),
    persistCheckedInputEventCallback
];

/**
 * Callback to return a list of input checkbox elements.
 *
 * @callback ToggleCheckedInputRange~querySelectorAllCallback
 *
 * @return {HTMLInputElement[]|NodeList}
 */

/**
 * A function that is called whenever a "click" event occurs.
 *
 * @callback ToggleCheckedInputRange~HandlerEventCallback
 *
 * @param  {Event} event - The event object.
 * @return {void}
 */

/**
 * An event listener object to toggle a range of checkboxes using the shift-key.
 *
 * @typedef {object} ToggleCheckedInputRange~EventListener
 *
 * @implements {EventListener}
 *
 * @property {HTMLInputElement}                                 lastInput               - The previously checked, or unchecked, input.
 * @property {HTMLElement}                                      commonAncestorContainer - The common ancestor that contains the input collection.
 * @property {ToggleCheckedInputRange~querySelectorAllCallback} querySelectorAll        - The default input collection handler.
 * @property {ToggleCheckedInputRange~HandlerEventCallback}     handleEvent             - The default event listener.
 */

/**
 * Creates an event listener object to toggle a range of checkboxes using the shift-key.
 *
 * @param  {ToggleCheckedInputRange~querySelectorAllCallback} [querySelectorAllFn] -
 *     A function to return a list of input checkbox elements.
 * @return {ToggleCheckedInputRange~EventListener}
 */
export function createEventListenerToToggleCheckedInputRange(querySelectorAllFn) {
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
 * Callback to resolve the storage item key.
 *
 * @callback PersistCheckedInput~KeyResolverCallback
 *
 * @param  {HTMLInputElement} input - The input element to persist.
 * @param  {Event}            event - The event object.
 * @return {string}
 */

/**
 * Callback to resolve the storage item value.
 *
 * @callback PersistCheckedInput~ValueResolverCallback
 *
 * @param  {HTMLInputElement} input - The input element to persist.
 * @param  {Event}            event - The event object.
 * @return {*}
 */

/**
 * A function that is called whenever a "change" event occurs.
 *
 * @callback PersistCheckedInput~HandlerEventCallback
 *
 * @param  {Event} event - The event object.
 * @return {void}
 */

/**
 * Callback to persist input checked state in web storage.
 *
 * @callback PersistCheckedInput~UpdateStoreCallback
 *
 * @param  {Event} event - The event object.
 * @return {void}
 */

/**
 * An event listener object to persist input checked state in web storage.
 *
 * @typedef {object} PersistCheckedInput~EventListener
 *
 * @implements {EventListener}
 *
 * @property {Storage}                                   storageArea   - The web storage object.
 * @property {PersistCheckedInput~KeyResolverCallback}   getStoreKey   - The default storage key resolver.
 * @property {PersistCheckedInput~ValueResolverCallback} getStoreValue - The default storage value resolver.
 * @property {PersistCheckedInput~HandlerEventCallback}  handleEvent   - The default event handler.
 * @property {PersistCheckedInput~UpdateStoreCallback}   updateStore   - The web storage object updater.
 */

/**
 * Creates an event listener object to persist input checked state in web storage.
 *
 * @param  {Storage}                                   store     - The web storage object.
 * @param  {PersistCheckedInput~KeyResolverCallback}   [keyFn]   - A storage key resolver.
 * @param  {PersistCheckedInput~ValueResolverCallback} [valFn]   - A storage value resolver.
 * @param  {PersistCheckedInput~HandlerEventCallback}  [proxyFn] - A proxy event handler function.
 *
 * @return {PersistCheckedInput~EventListener}
 */
export function createEventListenerToPersistCheckedInput(store, keyFn, valFn, proxyFn) {
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
 * Orders Tracker
 */
export default class Track
{
    /**
     * @property {HTMLTableElement[]} - One or more table elements.
     */
    tables;

    /**
     * @property {Storage} - A web storage object.
     */
    store;

    /**
     * Creates a new Tracker.
     *
     * @param {HTMLTableElement[]} tables - One or more table elements.
     * @param {Storage}            store  - A web storage object.
     */
    constructor(tables, store)
    {
        if (tables instanceof Element) {
            tables = [ tables ];
        }

        this.tables = tables;
        this.store  = store;

        for (const table of tables) {
            for (const tbody of table.tBodies) {
                for (const row of tbody.rows) {
                    try {
                        row.input = document.getElementById('ds-order-select-' + row.dataset.orderId);
                        if (row.input) {
                            row.input.checked = store.getItem(STORE_PREFIX + row.input.value);

                            row.classList.toggle('c-tr-todo', !row.input.checked);
                            row.classList.toggle('c-tr-done', row.input.checked);
                        }
                    } catch (error) {
                        console.error('[Bridges]', '[Table Body Row: ' + row.rowIndex + ']', error);
                    }
                }
            }

            this._registerEventListeners(table);

            table.classList.add('is-trackable');
        }
    }

    /**
     * Uninitialize the searcher.
     *
     * @return {void}
     */
    destroy() {
        for (const table of this.tables) {
            this._removeEventListeners(table);

            table.classList.remove('is-trackable');
        }
    }

    /**
     * Registers delegated event listeners on the table.
     *
     * @param  {HTMLTableElement} table - The target table element.
     * @return {void}
     */
    _registerEventListeners(table) {
        table.eventset = table.eventset || {};

        table.eventset.click = createEventListenerToToggleCheckedInputRange(checklistRangeQueryCallback);
        table.addEventListener('click', table.eventset.click, {
            passive: true
        });

        table.eventset.change = createEventListenerToPersistCheckedInput(...[ this.store, ...persistCheckedInputEventArgs ]);
        table.addEventListener('change', table.eventset.change, {
            passive: true
        });
    }

    /**
     * Removes delegated event listeners on the table.
     *
     * @param  {HTMLTableElement} table - The target table element.
     * @return {void}
     */
    _removeEventListeners(table) {
        if (table.eventset.click) {
            table.removeEventListener('click', table.eventset.click);
        }

        if (table.eventset.change) {
            table.removeEventListener('change', table.eventset.change);
        }
    }
}
