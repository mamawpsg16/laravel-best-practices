const readableDateOptions = {
    month: 'long',
    day: '2-digit',
    year: 'numeric',
};

const numericDateOptions = {
    month: 'numeric',
    day: '2-digit',
    year: 'numeric',
};

const timeOptions = {
    hour: 'numeric',
    minute: 'numeric',
    second: 'numeric',
};
const formatter = {
    formatReadableDate(date, locale = 'en-US', options = {}) {
        if (!date) return;
        const formatOptions = { ...readableDateOptions, ...options };
        return new Intl.DateTimeFormat(locale, formatOptions).format(new Date(date));
    },
    
    formatNumericDate(date, locale = 'en-US', options = {}) {
        if (!date) return;
        const formatOptions = { ...numericDateOptions, ...options };
        return new Intl.DateTimeFormat(locale, formatOptions).format(new Date(date));
    },

    formatReadableDateTime(date, locale = 'en-US', options = {}) {
        if (!date) return;
        date = new Date(date);
        const formatOptions = { ...readableDateOptions, ...options };
        const formattedDate = new Intl.DateTimeFormat(locale, formatOptions).format(date);
        const formattedTime = new Intl.DateTimeFormat(locale, timeOptions).format(date);
        return `${formattedDate} ${formattedTime}`;
    },

    formatNumericDateTime(date, locale = 'en-US', options = {}) {
        if (!date) return;
        date = new Date(date);
        const formatOptions = { ...numericDateOptions, ...options };
        const formattedDate = new Intl.DateTimeFormat(locale, formatOptions).format(date);
        const formattedTime = new Intl.DateTimeFormat(locale, timeOptions).format(date);
        return `${formattedDate} ${formattedTime}`;
    },

    capitalizeFirstLetter(word) {
        if(!word) return;
        return word ? word.charAt(0).toUpperCase() + word.toLowerCase().slice(1) : '';
    },

    extractFieldsFromObjects(fields, details) {
        const data = details.map(obj => {
            const newObj = {};
            fields.forEach(field => {
                if (obj.hasOwnProperty(field)) {
                    newObj[field] = obj[field];
                }
            });
            return newObj;
        });

        return data;
    },

    formatToSql(date){
        if(!date instanceof Date){
            date = new Date(date);
        }
        
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');

        return `${year}-${month}-${day}`;
    }
}

export default formatter;