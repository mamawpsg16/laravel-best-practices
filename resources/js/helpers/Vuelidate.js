export const checkValidity = function (v$, parentProperty = null, dataProperty, validations = []) {
    let invalid = false;
    const isDirty = parentProperty ? v$[parentProperty][dataProperty].$dirty : v$[dataProperty].$dirty;
    if (isDirty) {
        for (const validation of validations) {
            const isInvalid = parentProperty ? v$[parentProperty][dataProperty][validation].$invalid : v$[dataProperty][validation].$invalid;

            if (isInvalid) {
                invalid = true;
                break;
            }
        }
    }
    return invalid;
};

export const checkLoopValidity = function (v$, parentProperty = null, dataProperty, validations = [], index = 0) {
    let invalid = false;
    const isDirty = v$[parentProperty][index][dataProperty].$dirty;
    if (isDirty) {
        for (const validation of validations) {
            const isInvalid = v$[parentProperty][index][dataProperty].$invalid;

            if (isInvalid) {
                invalid = true;
                break;
            }
        }
    }

    return invalid;
}


export const checkLoopErrors = function(v$,parentProperty = null, dataProperty, validations = [], index = null){
    let invalid = [];
    const isDirty = v$[parentProperty][index][dataProperty].$dirty;
    if (isDirty) {
        for (const validation of validations) {
            const isInvalid = v$[parentProperty][index][dataProperty][validation].$invalid;
            if(isInvalid){
                invalid.push(validation);
            }
        }
    }
    return invalid;
}