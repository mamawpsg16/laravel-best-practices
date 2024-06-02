export const deepClone = (data) => JSON.parse(JSON.stringify(data));

export const nestedDeepClone = (data) => structuredClone(data);

export const customDeepClone = function(obj) {
    // Handle null or undefined
    if (obj === null || obj === undefined) {
        return obj;
    }

    // Handle primitive types
    if (typeof obj !== 'object' && typeof obj !== 'function') {
        return obj;
    }

    // Handle functions
    if (typeof obj === 'function') {
        return obj.bind({});
    }

    // Handle Dates
    if (obj instanceof Date) {
        return new Date(obj.getTime());
    }

    // Handle Arrays
    if (Array.isArray(obj)) {
        return obj.map(item => customDeepClone(item));
    }

    // Handle Map
    if (obj instanceof Map) {
        const clonedMap = new Map();
        obj.forEach((value, key) => {
            clonedMap.set(key, customDeepClone(value));
        });
        return clonedMap;
    }

    // Handle Set
    if (obj instanceof Set) {
        const clonedSet = new Set();
        obj.forEach(value => {
            clonedSet.add(customDeepClone(value));
        });
        return clonedSet;
    }

    // Handle objects
    const clonedObj = {};
    for (let key in obj) {
        if (obj.hasOwnProperty(key)) {
            clonedObj[key] = customDeepClone(obj[key]);
        }
    }

    return clonedObj;
}