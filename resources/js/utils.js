import {customRef} from "vue";

export const getLocale = () => document.documentElement.lang ?? 'ru';

export const periodFormat = (period, short = false) => {
    let _period = period;
    if (_period instanceof Array) {
        _period = {
            from: _period[0],
            to: _period[1] ?? _period[0],
        };
    }

    if (typeof _period === 'string') {
        _period = _period.split(':');
        _period = {
            from: _period[0],
            to: _period[1] ?? _period[0],
        };
    }

    if (isEmpty2(_period.to)) {
        return dateFormat(_period.from, short);
    }

    let from = dateFormat(_period.from, short);
    let to = dateFormat(_period.to, short);

    if (from === to) {
        return from;
    }

    to = to.split(' ');
    from = from.split(' ');

    to.pop();
    from.pop();

    // TODO: Для en локали криво форматируется

    let other = [];
    for (let i = to.length - 1; i >= 0; i--) {
        if (to[i] !== from[i]) {
            break;
        }

        other.push(to[i]);
        to.pop();
        from.pop();
    }

    return `${from.join(' ')} - ${to.join(' ')} ${other.reverse().join(' ')}`;
};

export const isEmpty = (val) => (
    val === null
    || val === undefined
    || (val instanceof Array && !val.length)
    || (val instanceof Object && Object.getPrototypeOf(val) === Object.prototype && !Object.keys(val).length)
    || (val instanceof String && (
        !val.length
        || val === '0'
    ))
    || (val instanceof Boolean && val === false)
    || (val instanceof Number && (
        !val
        || isNaN(val)
    ))
);

export const isEmpty2 = (val) => (
    val === null
    || val === undefined
    || val === 0
    || val === ''
    || (val instanceof Array && !val.length)
    || (val instanceof Object && Object.getPrototypeOf(val) === Object.prototype && !Object.keys(val).length)
    || (typeof val == 'string' && (
        !val.length
        || val === '0'
    ))
    || (typeof val == 'boolean' && val === false)
    || (typeof val == 'number' && (
        !val
        || isNaN(val)
    ))
);

export const dateToUserTzString = (date, options = null) => {
    const tz = Intl.DateTimeFormat().resolvedOptions().timeZone ?? 'Europe/Moscow';

    return (new Date(date)).toLocaleString('ru-RU', {timeZone: tz, ...options})
};

export const dateFormat = (date, short = false) => {
    if (!(date instanceof Date)) {
        date = new Date(date);
    }

    // Костыль :))
    date.setUTCHours(10);

    return date.toLocaleDateString(getLocale(), {
        day: short ? 'numeric' : '2-digit',
        month: short ? 'short' : 'long',
        year: 'numeric',
    }).replaceAll(',', '');
}

// Зачем оно вообще надо?)
// export const countDecimals = (val) => {
//     if (typeof val !== 'number') {
//         throw new TypeError("val must be a number.");
//     }
//
//     if (Math.floor(val) === val) return 0;
//
//     let str = val.toString();
//     if (str.indexOf(".") !== -1 && str.indexOf("-") !== -1) {
//         return str.split("-")[1] || 0;
//     } else if (str.indexOf(".") !== -1) {
//         return str.split(".")[1].length || 0;
//     }
//     return str.split("-")[1] || 0;
// }

export const r = (val, r = 2, type = 'r') => {
    val = n(val);

    const func = {
        r: Math.round,
        c: Math.ceil,
        f: Math.floor,
    }[type] ?? Math.round;

    if (r === 0) {
        return func(val);
    }

    let d = Math.pow(10, r);
    return func(val * d) / d;
};

export const n = (num) =>
    isEmpty2(num)
        ? 0
        : Number(num);

export const diffPercents = (from, to, signed = false) => {
    let res;
    if (n(to) === 0 && n(from) === 0) {
        res = 0;
    } else if (n(to) === 0) {
        res = -100;
    } else if (n(from) === 0) {
        res = 100;
    } else {
        res = Math.round(((to - from) / from) * 100)
    }

    return signed ? res : Math.abs(res);
};

export const separateNumber = (_num) => {
    let num = String(_num).split('.')[0];
    if (num.length <= 3) {
        return _num;
    }

    let result = [];
    let cnt = num.length;
    while (cnt > 3) {
        cnt -= 3;
        result.push(num.substring(cnt, cnt + 3))
    }
    result.push(num.substring(0, cnt));
    result = result.reverse().join(' ');

    return result;
};

export const currencyToSign = (currency) => {
    return {
        RUB: '₽',
        USD: '$',
        EUR: '€',
    }[currency] ?? '₽';
};

export const formatTimeLength = (seconds) => {
    seconds = r(seconds);

    let hours = r(seconds / 3600, 0, 'f');
    let mins = r((seconds % 3600) / 60, 0, 'f');
    let secs = r(seconds % 60, 0, 'f');

    let res = [];

    if (hours > 0) {
        res.push(`${hours}ч`);
    }
    if (mins > 0) {
        res.push(`${mins}м`);
    }
    if (secs > 0) {
        res.push(`${secs}с`);
    }

    if (!res.length) {
        return '0c';
    }

    return res.join(' ');
};

export const clamp = (val, min, max) => Math.min(Math.max(val, min), max);

export const strToIntHash = (str) => {
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = str.charCodeAt(i) + ((hash << 5) - hash);
    }
    return hash;
};

export const intToRGB = (i) => {
    const c = (i & 0x00FFFFFF)
        .toString(16)
        .toUpperCase();

    return "00000".substring(0, 6 - c.length) + c;
};

export const strToHashColor = (str) => intToRGB(strToIntHash(str));

export const ls = {

    get(key) {
        return localStorage.getItem(key);
    },
    getN(key) {
        return n(ls.get(key));
    },
    getB(key) {
        return Boolean(ls.getN(key));
    },
    getJ(key) {
        return JSON.parse(ls.get(key));
    },

    set(key, val) {
        localStorage.setItem(key, val);
    },
    setN(key, val) {
        ls.set(key, n(val));
    },
    setB(key, val) {
        ls.set(key, val ? '1' : '0');
    },
    setJ(key, val) {
        return ls.set(key, JSON.stringify(val));
    },

    // readonly
    STR: '',
    BOOL: 'B',
    JSON: 'J',
    NUM: 'N',

    use(key, type) {
        type = type.toUpperCase();
        return customRef(() => {
            return {
                get: () => ls[`get${type}`](key),
                set: (val) => ls[`set${type}`](key, val),
            }
        });
    },
};

export const parseParamsFromQueryString = (queryString) => {
    if (isEmpty(queryString)) {
        return {};
    }

    let rawParams = queryString.split('&');
    let params = {};

    for (let i in rawParams) {
        let param = rawParams[i].split('=', 2);
        if (!param.length || param[0] === '') {
            continue;
        }
        if (param.length === 1) {
            params[param[0]] = true;
        }
        params[param[0]] = param[1];
    }

    return params;
};

export const parseParamsFromUrl = (url) => parseParamsFromQueryString(url.split('?', 2)[1] ?? '');

export const analyticsCalcSummary = (items) => {
    let summary = null;
    let countNonZeroMetrics = {};

    for (let i in items) {
        let metrics = {...items[i].metrics};

        if (isEmpty2(summary)) {
            summary = {};
        }

        let keys = ['clicks', 'requests', 'expenses', 'income', 'purchases', 'impressions', 'ctr', 'cpc', 'drr', 'cpl'];
        for (let key of keys) {
            summary[key] = r((summary[key] ?? 0) + (metrics[key] ?? 0), 2);
            if (!isEmpty2(metrics[key])) {
                countNonZeroMetrics[key] = (countNonZeroMetrics[key] ?? 0) + 1;
            }
        }
    }

    if (isEmpty2(summary)) {
        return;
    }

    const avg = (key) => countNonZeroMetrics[key] && summary[key] ? r(summary[key] / countNonZeroMetrics[key], 2) : 0;
    const percent = (key1, key2) => summary[key2] && summary[key1] ? r((summary[key1] / summary[key2]) * 100, 2) : 0;

    summary.cpc = avg('cpc');
    summary.ctr = avg('ctr');
    summary.cpl = avg('cpl');
    summary.drr = avg('drr');

    // summary.cpc = summary?.clicks && summary?.expenses ? r(summary.expenses / summary.clicks, 2) : 0;
    // summary.cpl = summary?.requests && summary?.expenses ? r(summary.expenses / summary.requests, 2) : 0;
    // summary.drr = summary?.income && summary?.expenses ? r((summary.expenses / summary.income) * 100, 2) : 0;
    summary.cr = percent('requests', 'clicks');
    // summary.ctr = summary.clicks && summary.impressions ? r((summary.clicks / summary.impressions) * 100, 2) : 0;

    return summary;
};

export const Utils = {
    getLocale,
    periodFormat,
    dateFormat,
    r,
    n,
    diffPercents,
    separateNumber,
    clamp,
    ls,
    isEmpty,
    dateToUserTzString,
    strToHashColor,
    intToRGB,
    strToIntHash,
    parseParamsFromUrl,
    parseParamsFromQueryString,
    isEmpty2,
    analyticsCalcSummary,
};

export default {
    install(app) {
        app.config.globalProperties.$utils = Utils;
        app.config.globalProperties.$u = Utils;
    }
};

export const ymReachGoal = (target) => {
    try {
        ym(87284038, 'reachGoal', target);
    } catch (e) {
        console.error(e);
    }
}
