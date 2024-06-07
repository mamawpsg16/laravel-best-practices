const callOptimization = {
    throttle(func, delay) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, delay);
            }
        };
    },

    debounce(func, delay) {
        let timeOutId;
        return function(){
            const context = this;
            const args = arguments;
            clearTimeout(timeOutId);
            timeOutId = setTimeout(() => {
                func.call(context, ...args)
            }, delay);
        }
    }
}

export default callOptimization;