function createNewEvent(eventName) {
    var event;
    if (typeof(Event) === 'function') {
        event = new Event(eventName);
    } else {
        event = document.createEvent('Event');
        event.initEvent(eventName, true, true);
    }
    return event;
}

export const WilloCartUpdated = createNewEvent('willo-cart-updated')
