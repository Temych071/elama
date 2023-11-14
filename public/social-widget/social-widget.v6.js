class SocialWidget {

    elRoot = null;
    elRootBtn = null;
    elPopup = null;
    elWelcome = null;
    apiUrl;
    widgetId;
    data;

    constructor(widgetId, apiUrl) {
        this.widgetId = widgetId;
        this.apiUrl = apiUrl;

        fetch(`${this.apiUrl}/${this.widgetId}`)
            .catch(() => {
                console.log('Can\'t load DailyGrow social widget.');
                console.log('Check specified index of widget and API url.');
                console.log('apiUrl = ', this.apiUrl);
                console.log('widgetId = ', this.widgetId);
            })
            .then(res => res.json())
            .then(res => {
                this.data = res;
                document.querySelector('body').appendChild(this.renderWidget());

                this.trackView();
            });
    }

    trackClick() {
        // noinspection JSIgnoredPromiseFromCall
        fetch(`${this.apiUrl}/${this.widgetId}/track/click`, {method: 'post'});
    }

    trackView() {
        // noinspection JSIgnoredPromiseFromCall
        fetch(`${this.apiUrl}/${this.widgetId}/track/view`, {method: 'post'});
    }

    renderWidget() {
        this.elRoot = document.createElement('dg-div');
        this.elRoot.classList.add('dg__social-widget');
        this.elRoot.style.bottom = `${this.data.view_settings.margin_y - 5}px`;
        switch (this.data.view_settings.position) {
            case 'left':
                this.elRoot.style.left = `${this.data.view_settings.margin_x - 5}px`;
                break;

            case 'right':
                this.elRoot.style.right = `${this.data.view_settings.margin_x - 5}px`;
                break;
        }

        if (this.data.view_settings.popup_enabled) {
            this.elRoot.appendChild(this.renderSocialWidgetPopup());
        } else {
            this.elRoot.appendChild(this.renderButtonsPopup());
        }
        this.elPopup.style.display = 'none';

        this.elRootBtn = document.createElement('dg-button');
        this.elRootBtn.classList.add('dg__social-widget__root-button');
        this.elRootBtn.addEventListener('click', () => this.clickRootBtn());
        this.elRootBtn.style.height = this.data.view_settings.avatar_size + 'px';
        this.elRootBtn.style.width = this.data.view_settings.avatar_size + 'px';

        if (
            this.data.messengers_list.includes('tg')
            && this.data.messengers_list.includes('wa')
        ) {
            this.elRootBtn.setAttribute('data-type', 'tg-wa');
        } else if (this.data.messengers_list.includes('tg')) {
            this.elRootBtn.setAttribute('data-type', 'tg');
        } else {
            this.elRootBtn.setAttribute('data-type', 'wa');
        }

        switch (this.data.view_settings.root_btn_type ?? 'animated-icon') {
            case 'animated-icon':
                this.elRootBtn.setAttribute('data-animated', 'true');
                break;
            case 'static-icon':
                this.elRootBtn.setAttribute('data-animated', 'false');
                break;
            case 'photo':
                this.elRootBtn.style['background-image'] = `url(${this.data.view_settings.avatar_url})`;
                this.elRootBtn.style['border-color'] = this.data.view_settings.avatar_border_color;
                this.elRootBtn.setAttribute('data-photo', '');
                break;
        }

        if (this.data.view_settings.welcome_enabled) {
            this.elRoot.appendChild(this.renderWelcome(this.data.view_settings));
        }

        this.elRoot.appendChild(this.elRootBtn);

        return this.elRoot;
    }

    clickRootBtn() {
        if (!this.data.view_settings.popup_enabled && this.data.messengers_list?.length === 1) {
            this.clickButton(this.data.messengers_list[0]);
        } else {
            this.showPopup();
        }
    }

    renderWelcome(widgetView) {
        this.elWelcome = document.createElement('dg-div');
        this.elWelcome.classList.add('dg__social-widget__welcome');
        this.elWelcome.setAttribute('data-should-show', '1');
        this.elWelcome.setAttribute('data-widget-pos', widgetView.position);
        this.elWelcome.innerHTML = `
            <dg-div class="dg__social-widget__welcome__close"></dg-div>
            <dg-div class="dg__social-widget__welcome__text">${widgetView.welcome_message}</dg-div>
        `;
        this.elWelcome.style.display = 'none';

        this.elWelcome
            .querySelector('.dg__social-widget__welcome__close')
            ?.addEventListener('click', () => {
                if (this.elWelcome) {
                    this.elWelcome.style.display = 'none';
                    this.elWelcome.setAttribute('data-should-show', '0');
                }
            });

        this.elWelcome
            .querySelector('.dg__social-widget__welcome__text')
            ?.addEventListener('click', () => this.showPopup());

        setTimeout(() => {
            if (this.elWelcome && this.elWelcome.getAttribute('data-should-show') === '1') {
                this.elWelcome.style.display = 'flex';
                this.elWelcome.setAttribute('data-should-show', '0');
            }
        }, widgetView.welcome_delay * 1000);

        return this.elWelcome;
    }

    renderButton(type, disabled = false) {
        let button = document.createElement('dg-button');
        button.classList.add('dg__social-widget__button');

        if (!disabled) {
            button.addEventListener('click', () => this.clickButton(type));
        }

        switch (type) {
            case 'wa':
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" height="128px" viewBox="0 0 128 128" width="128px" xml:space="preserve">
                        <circle cx="64" cy="64" fill="#25D366" r="64"/>
                        <g>
                            <path
                                d="M92.346,35.49c-7.522-7.53-17.523-11.678-28.179-11.683c-21.954,0-39.826,17.868-39.833,39.831 c-0.004,7.022,1.831,13.875,5.316,19.913L24,104.193l21.115-5.538c5.819,3.171,12.369,4.844,19.036,4.847h0.017l0,0 c21.954,0,39.823-17.871,39.832-39.833C104.005,53.027,99.864,43.019,92.346,35.49 M64.168,96.774h-0.013 c-5.943-0.002-11.769-1.598-16.853-4.614l-1.209-0.718l-12.53,3.287l3.343-12.216l-0.787-1.256 c-3.315-5.27-5.066-11.361-5.062-17.619c0.006-18.253,14.859-33.104,33.121-33.104c8.844,0.002,17.155,3.451,23.407,9.71 c6.251,6.258,9.691,14.575,9.689,23.422C97.268,81.922,82.415,96.774,64.168,96.774 M82.328,71.979 c-0.996-0.499-5.889-2.904-6.802-3.239c-0.913-0.332-1.574-0.497-2.238,0.499s-2.571,3.239-3.153,3.903 c-0.58,0.664-1.16,0.748-2.156,0.249s-4.202-1.549-8.001-4.941c-2.96-2.637-4.958-5.899-5.538-6.895s-0.062-1.533,0.437-2.03 c0.448-0.446,0.996-1.162,1.493-1.744c0.497-0.582,0.663-0.997,0.995-1.66c0.332-0.664,0.167-1.245-0.083-1.743 c-0.25-0.499-2.24-5.398-3.068-7.391c-0.809-1.941-1.629-1.678-2.239-1.708c-0.582-0.028-1.245-0.036-1.908-0.036 c-0.663,0-1.742,0.249-2.654,1.246c-0.911,0.996-3.483,3.403-3.483,8.304c0,4.898,3.566,9.632,4.064,10.295 c0.498,0.663,7.018,10.718,17.002,15.029c2.374,1.024,4.229,1.637,5.674,2.097c2.384,0.759,4.554,0.65,6.27,0.394 c1.912-0.285,5.888-2.407,6.719-4.732c0.829-2.324,0.829-4.316,0.578-4.732C83.986,72.727,83.322,72.478,82.328,71.979"
                                fill="#FFFFFF"
                            />
                        </g>
                    </svg>
                `;
                break;
            case 'tg':
                button.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 240 240" xml:space="preserve">
                        <g>
                            <linearGradient id="Oval_1_" gradientUnits="userSpaceOnUse" x1="-838.041" y1="660.581" x2="-838.041" y2="660.3427" gradientTransform="matrix(1000 0 0 -1000 838161 660581)">
                                <stop offset="0" style="stop-color:#2AABEE"/>
                                <stop offset="1" style="stop-color:#229ED9"/>
                            </linearGradient>
                            <circle id="Oval" fill-rule="evenodd" clip-rule="evenodd" fill="url(#Oval_1_)" cx="120.1" cy="120.1" r="120.1"/>
                            <path
                                id="Path-3"
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                fill="#FFFFFF"
                                d="M54.3,118.8c35-15.2,58.3-25.3,70-30.2 c33.3-13.9,40.3-16.3,44.8-16.4c1,0,3.2,0.2,4.7,1.4c1.2,1,1.5,2.3,1.7,3.3s0.4,3.1,0.2,4.7c-1.8,19-9.6,65.1-13.6,86.3 c-1.7,9-5,12-8.2,12.3c-7,0.6-12.3-4.6-19-9c-10.6-6.9-16.5-11.2-26.8-18c-11.9-7.8-4.2-12.1,2.6-19.1c1.8-1.8,32.5-29.8,33.1-32.3 c0.1-0.3,0.1-1.5-0.6-2.1c-0.7-0.6-1.7-0.4-2.5-0.2c-1.1,0.2-17.9,11.4-50.6,33.5c-4.8,3.3-9.1,4.9-13,4.8 c-4.3-0.1-12.5-2.4-18.7-4.4c-7.5-2.4-13.5-3.7-13-7.9C45.7,123.3,48.7,121.1,54.3,118.8z"
                            />
                        </g>
                    </svg>
                `;
                break;
        }

        return button;
    }

    getMessengerUrl(type) {
        return `${this.apiUrl}/${this.widgetId}/redirect?` + [
            `messenger=${type}`,
            `current_url=${encodeURIComponent(window.location.href)}`,
            `referer=${document.referrer}`,
            `device=${this.getUserDevice()}`,
        ].join('&');
    }

    getUserDevice() {
        const ua = navigator.userAgent;
        if (/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i.test(ua)) {
            return "tablet";
        }

        if (/Mobile|iP(hone|od)|Android|BlackBerry|IEMobile|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/.test(ua)) {
            return "mobile";
        }

        return "desktop";
    }

    clickButton(type) {
        // this.trackClick();
        this.emitStatsEvent(this.getMessengerClickEvent(type));
        window.open(this.getMessengerUrl(type), '_blank').focus();
    }

    renderSocialWidgetPopup() {
        let widgetView = this.data.view_settings;

        this.elPopup = document.createElement('dg-div');
        this.elPopup.classList.add('dg__social-widget__popup');

        this.elPopup.innerHTML = `
            <dg-button class="dg__social-widget__popup__close">
                <svg width="18px" height="18px" style="display: block; stroke-width: 1" viewBox="100 30 500 500" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" stroke="#0000007F">
                        <path transform="matrix(23.333 0 0 23.333 70 0)" d="m21.5 12c0 5.2467-4.2534 9.5001-9.5001 9.5001s-9.5001-4.2534-9.5001-9.5001 4.2534-9.5001 9.5001-9.5001 9.5001 4.2534 9.5001 9.5001"/>
                        <path transform="matrix(23.333 0 0 23.333 70 0)" d="m8.0001 8.0001 7.9999 7.9999" stroke-linecap="square"/>
                        <path transform="matrix(23.333 0 0 23.333 70 0)" d="m8.0001 16 7.9999-7.9999" stroke-linecap="square"/>
                    </g>
                </svg>
            </dg-button>

            ${widgetView.avatar_url !== null ? `
                <!--suppress CssInvalidPropertyValue -->
                <img
                    src="${widgetView.avatar_url}"
                    alt="avatar"
                    class="dg__social-widget__popup__avatar"
                    style="
                        border-color: ${widgetView.avatar_border_color};
                    "
                >
            ` : ''}

            <dg-div class="dg__social-widget__popup__title">${widgetView.popup_title}</dg-div>
            <dg-div class="dg__social-widget__popup__message">${widgetView.popup_message}</dg-div>

            <hr/>

            <dg-div class="dg__social-widget__buttons"></dg-div>
            ${widgetView.popup_phone ? `
                <a class="dg__social-widget__popup__phone" href="tel:${widgetView.popup_phone.replaceAll(/[^+0-9]/g, '')}">${widgetView.popup_phone}</a>
            ` : ''}

            <a class="dg__social-widget__popup__copyright"
               href="https://dailygrow.ru/"
               target="_blank"
               style="display: ${widgetView.disable_copyright ? 'none' : 'block'}"
            >Сделано в DailyGrow</a>
        `;

        let buttons = this.elPopup.querySelector('.dg__social-widget__buttons');

        for (let btnType of this.data.messengers_list) {
            buttons.appendChild(this.renderButton(btnType));
        }

        this.elPopup.querySelector('.dg__social-widget__popup__close')
            .addEventListener('click', () => this.closePopup());

        return this.elPopup;
    }

    renderButtonsPopup() {
        this.elPopup = document.createElement('dg-div');
        this.elPopup.innerHTML = `
            <dg-div class="dg__social-widget__simple-popup">
                <dg-span class="dg__social-widget__buttons"></dg-span>
                <dg-span class="dg__social-widget__v-line" style="color: lightgray"></dg-span>
                <dg-span class="dg__social-widget__simple-popup__close">x</dg-span>
            </dg-div>
        `;

        let buttons = this.elPopup.querySelector('.dg__social-widget__buttons');


        for (let btnType of this.data.messengers_list) {
            buttons.appendChild(this.renderButton(btnType));
        }

        this.elPopup.querySelector('.dg__social-widget__simple-popup__close')
            .addEventListener('click', () => this.closePopup());

        return this.elPopup;
    }

    showPopup() {
        this.elPopup.style.display = 'block';
        this.elRootBtn.style.display = 'none';

        if (this.elWelcome) {
            this.elWelcome.style.display = 'none';
            this.elWelcome.setAttribute('data-should-show', '0');
        }

        this.emitStatsEvent(this.EVENT_OPEN);
    }

    closePopup() {
        this.elPopup.style.display = 'none';
        this.elRootBtn.style.display = 'block';
    }

    EVENT_OPEN = 'dg_social_open';
    EVENT_CLICK_WA = 'dg_social_click_wa';
    EVENT_CLICK_TG = 'dg_social_click_tg';

    getMessengerClickEvent(type) {
        return {
            tg: this.EVENT_CLICK_TG,
            wa: this.EVENT_CLICK_WA,
        }[type];
    }

    emitStatsEvent(event) {
        for (let counter of this.data.stats_counters) {
            const id = counter['counter_id'];
            switch (counter['type']) {
                case 'ga':
                    if (typeof gtag === 'function') {
                        gtag('event', event, {send_to: id});
                    }
                    break;
                case 'ym':
                    if (typeof ym === 'function') {
                        ym(id, 'reachGoal', event);
                    }
                    break;
            }
            // console.log(`Emitted ${event} event for counter ${id} (${counter['type']}) `);
        }
    }
}

(() => {
    window.dgSocial = new SocialWidget(window.dgSocialWidgetData.widgetId, window.dgSocialWidgetData.apiUrl);
})();
