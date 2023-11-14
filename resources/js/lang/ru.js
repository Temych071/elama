import seoAudit from "./ru/seo-audit";

export default {
    auth: {
        login: {
            metaTitle: 'Вход',

            title: 'Вход в {appName}',
            fields: {
                email: 'Email',
                pass: 'Пароль',
            },
            login: 'Войти',
            byGoogle: '@:auth.login.login через Google',
            register: 'Зарегистрироваться',
            forgotPass: 'Забыли пароль?',
        },
        reg: {
            metaTitle: 'Регистрация',

            title: 'Регистрация в {appName}',
            fields: {
                email: 'Email',
                phone: 'Телефон',
                pass: 'Пароль',
                name: 'Ваше имя',
            },
            register: 'Зарегистрироваться',
            byGoogle: '@:auth.reg.register через Google',
            agreement: 'Отправляя сведения через электронную форму ' +
                'вы соглашаетесь с <a href="https://dailygrow.ru/oferta.pdf/" target="_blank" class="text-primary">условиями оферты</a> ' +
                'и даете согласие на ' +
                '<a href="https://dailygrow.ru/privacy_policy.pdf/" target="_blank" class="text-primary">обработку персональных данных на условии Политики</a>',

            loginLink: {
                prefix: 'Уже есть аккаунт? ',
                linkText: 'Войти',
            },
        },
        forgot_pass: {
            title: 'Забыли пароль?',
            header: '@:auth.forgot_pass.title',
            form: {
                email: 'Email',
            },
            send: 'Отправить ссылку для сброса пароля',
            text: 'Забыли пароль? ' +
                'Введите электронный адрес, указанный при регистрации, ' +
                'на него будет отправлена ссылка на сброс пароля, ' +
                'перейдя по которой сможете ввести новый пароль.'
        },
        reg_finish: {
            title: 'Завершение регистрации',
            header: 'Регистрация в {appName}',
            form: {
                phone: 'Телефон',
                pass: 'Пароль',
            },
            finish_register: 'Завершить регистрацию',
        },
        reset_pass: {
            title: 'Сброс пароля',
            header: '@:auth.reset_pass.title',
            form: {
                new_pass: 'Новый пароль',
                confirm_pass: 'Подтверждение пароль',
            },
            reset_pass: 'Сбросить пароль',
        },
        verify_email: {
            title: 'Подтверждение E-Mail',
            header: '@:auth.verify_email.title',
            send_again: 'Отправить письмо снова',
            send_again_msg: 'Новое письмо со ссылкой для подтверждения отправлено на электронную почту, указанную при регистрации.',
            logout: 'Выйти из аккаунта',
            text: 'Спасибо за регистрацию! ' +
                'Перед началом работы подтвердите указанную при регистрации электронную почту. ' +
                'Для этого перейдите по ссылке в отправленном на указанный адрес письме.',
        },
    },
    header: {
        menu: {
            projectsSettings: 'Настройка проектов',
            account: 'Аккаунт',
            users: 'Пользователи',
            balance: 'Оплата и баланс',
            notifications: 'Уведомления',
            integration: 'Интеграция',
        },
        notifications: {
            new: 'Новые',
            integrations: 'Интеграции',
        },
        days_left: '0 дней | {n} день | {n} дня | {n} дней',
    },
    sidebar: {
        campaigns: {
            edit: 'Редактировать',
        },
        menu: {
            browse: 'Дашборд',
            checks: 'Аудит',
            linkChecks: 'Проверка ссылок',
            settings: 'Настройки',
            analytics: 'Аналитика',
            help: 'Поддержка',
            plan: 'Тариф',
        },
    },
    campaigns: {
        members: {
            roles: {
                owner: 'Владелец',
                member: 'Участник',
            },
        },
        settings: {
            title: 'Настройки проектов',
            header: 'Настройки',
            project: 'Настройки проекта',
            buttons: {
                delete: 'Удалить проект',
                edit: 'Редактировать проект',
                sources: 'Редактировать',
                create: 'Создать проект',
            },
            emptyText: 'Вы еще не добавили ни одного проекта.',

            create: {
                title: 'Добавление проекта',
                header: 'Добавить проект',
                placeholder: 'Название',
                button: 'Добавить',
            },
            delete: {
                title: 'Удаление проекта',
                header: 'Удалить проект',
                button: 'Удалить',
            },
            edit: {
                title: 'Изменение проекта',
                header: 'Изменить проект',
                placeholder: 'Название',
                button: 'Изменить',
            },
        },

        feed: {
            btn: {
                connect: 'Подключить'
            },
            audit: {
                title: 'Аудит',
                name: 'Аудит',
            },
            new_users: {
                title: 'Новые пользователи',
                name: 'Количество новых пользователей',
                text: {
                    down: '@:campaigns.feed.new_users.name снизилось на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.new_users.name увеличилось на <span class="font-bold">{number}%</span>',
                },
            },
            avg_visit_duration: {
                title: 'Время на сайте',
                name: 'Средняя продолжительность сеанса',
                text: {
                    down: '@:campaigns.feed.avg_visit_duration.name снизилась на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.avg_visit_duration.name увеличилась на <span class="font-bold">{number}%</span>',
                },
            },
            page_views: {
                title: 'Просмотры',
                name: 'Количество просмотров страниц',
                text: {
                    down: '@:campaigns.feed.page_views.name снизилось на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.page_views.name увеличилось на <span class="font-bold">{number}%</span>',
                },
            },
            visits: {
                title: 'Визиты',
                name: 'Количество посещений сайта',
                text: {
                    down: '@:campaigns.feed.page_views.name снизилось на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.page_views.name увеличилось на <span class="font-bold">{number}%</span>',
                },
            },
            bounce_rate: {
                title: 'Отказы',
                name: 'Показатель отказов',
                text: {
                    down: '@:campaigns.feed.bounce_rate.name снизился на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.bounce_rate.name увеличился на <span class="font-bold">{number}%</span>',
                },
            },
            reaches: {
                title: 'Заявки',
                name: 'Количество заявок',
                text: {
                    down: '@:campaigns.feed.reaches.name уменьшилось на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.reaches.name увеличилось на <span class="font-bold">{number}%</span>',
                },
            },
            conversion_rate: {
                title: 'Конверсия',
                name: 'Показатель конверсии сайта',
                text: {
                    down: '@:campaigns.feed.conversion_rate.name снизился на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.conversion_rate.name увеличился на <span class="font-bold">{number}%</span>',
                },
            },
            mobile_traffic: {
                title: 'Мобильный трафик',
                name: 'Мобильный трафик',
                text: {
                    down: '@:campaigns.feed.mobile_traffic.name снизился на <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.mobile_traffic.name увеличился на <span class="font-bold">{number}%</span>',
                },
            },
            depth: {
                title: 'Глубина',
                name: 'Глубина',
                text: {
                    down: 'Количество просмотренных страниц снизилось на <span class="font-bold">{number}%</span>',
                    up: 'Количество просмотренных страниц увеличилось на <span class="font-bold">{number}%</span>',
                },
            },
            devices: {
                title: 'Трафик по устройствам',
            },
            city_conversion_rate: {
                title: 'Топ-5 регионов по конверсии в заявку',
            },
            city_new_users: {
                title: 'Регионы по новым пользователям',
            },
            city_reaches: {
                title: 'Регионы по заявкам',
            },
            visitors: {
                title: 'Посетители'
            },
            purchases: {
                title: 'Покупки'
            },
            income: {
                title: 'Доход'
            },
            convsNotLoaded: 'Данные не загружены...',

            refresh: 'Обновить',
            refreshNotAvailable: 'Недоступно',
            refreshLoading: 'Загрузка...',
            nextRefreshAt: 'Вы можете вручную обновлять данные не чаще одного раза в {freq}ч, следующее обновление в {at}.',

            deviceFooter: '{prePeriod} по сравнению с {selPeriod}',
            footer:
                '{prePeriod} <span class="text-primary">({preConv})</span> ' +
                'по сравнению c {selPeriod} <span class="text-primary">({selConv})</span>',
            footerNotification:
                '<div class="flex flex-row"><span class="font-normal text-xs text-[#6C757D]">{preConv}</span>' + '<img class="mx-2" src="/icons/full-arrow-right.svg" alt=""/>' + '<span class="font-normal text-xs text-[#6C757D]">{selConv}</span></div>',
        },
        planfact: {
            fields: {
                expenses: 'Расходы',
                income: 'Доходы',
                requests: 'Заявки',
                clicks: 'Клики',
                cpl: 'CPL (цена за заявку)',
                cpc: 'CPC (цена клика)',
                cr: 'CR (конверсия)',
                drr: 'ДРР (доля рекламных расходов)',
            },
            fieldsShort: {
                expenses: 'Расходы',
                income: 'Доходы',
                requests: 'Заявки',
                clicks: 'Клики',
                cpl: 'CPL',
                cpc: 'CPC',
                cr: 'CR',
                drr: 'ДРР',
            },
            settings: {
                title: 'Планы',
                header: 'План/факт',
                btnAdd: 'Добавить',
                btnEdit: 'Изменить',
                nullFilter: 'Все',
                notFound: 'Планы не заданы',

                table: {
                    header: {
                        name: 'Название',
                        campaign_name: 'Кампания',
                        utm_campaign: 'UTM Кампании',
                        sources: 'Кабинеты',
                        device: 'Устройство',
                        domain: 'Домен',
                        actions: '',
                    },
                },

                add: {
                    title: {
                        new: 'Создание плана',
                        edit: 'Изменение плана',
                    },
                    header: {
                        new: '@:campaigns.planfact.settings.add.title.new',
                        edit: '@:campaigns.planfact.settings.add.title.edit',
                    },
                    fieldPlaceholder: 'Введите планируемое значение показателя',
                    period: 'Период',
                    source: 'Рекламный канал',
                    sourcePlaceholder: 'Выберите рекламный канал',
                    allSources: 'Все каналы',

                    name: 'Название',
                    namePlaceholder: 'Введите название плана',

                    utmCampaign: 'UTM Кампания',
                    utmCampaignPlaceholder: 'Введите UTM метку кампании',

                    campaignName: 'Рекламная кампания',
                    campaignNamePlaceholder: 'Введите название рекламной кампании',

                    sources: 'Релкамные каналы',
                    selAllSources: 'Выбрать все каналы',

                    device: 'Устройство',

                    domain: 'Домен',
                    domainPlaceholder: 'Введите домен',

                    vkLeads: 'Лид Формы VK',

                    goals: 'Цели',

                    months: 'Периоды',

                    btn: {
                        add: 'Добавить',
                        edit: 'Сохранить',
                        cancel: 'Отменить',
                    },
                    addNextMonth: 'Добавить следующий месяц',
                    addPrevMonth: 'Добавить предыдущий месяц',
                    goalsNotFound: 'Цели не найдены',
                    allDevices: 'Все',
                    showExtendedSettings: 'Показать расширенные настройки',
                    copyPlanToNextMonth: 'Дублировать план на следующий месяц'
                },
            },
            block: {
                planLabel: 'План',
                domainLabel: 'Домен',
                title: 'План/Факт',
                hide: 'Скрыть',
                more: 'Подробнее',
                settings: 'Настройки',
                'paginator-of': 'из',
                charts: {
                    'plan-label': 'План',
                },
                notSpecifiedForSelectedMonth: 'План на указанный период не заполнен.',
                notSpecified: 'План/факт не настроен...',
                domainAll: 'Все',
                domainsNotFound: 'Домены не найдены'
            },
        },
        balances: {
            showButton: 'Показать баланс',
            rest: 'Остаток: {amount} {currency}',
            dailyBudget: 'Дн. бюджет: {dailyBudget} {currency}',
            selectLabel: 'Баланс'
        },
        browse: {
            title: 'Обзор'
        },
        analytics: {
            parameters: {
                expenses: 'Расходы',
                requests: 'Заявки',
                clicks: 'Клики',
                cpc: 'CPC',
                cr: 'CR',
                cpl: 'CPL',
                purchases: 'Продажи',
                income: 'Доход',
                drr: 'ДРР',
                impressions: 'Показы',
                ctr: 'CTR',
            }
        }
    },
    sources: {
        names: {
            'yandex-direct': 'Яндекс.Директ',
            'yandex-metrika': 'Яндекс.Директ',
            'google-ads': 'Google Ads',
            'google-analytics': 'Google Analytics',
            vk: 'ВКонтакте',
            fb: 'Facebook',
        },
        list: {
            title: 'Каналы',
            connect: 'Подключить',
            connected: 'Подключено',
            notConnected: 'Не подключено',
        },
        settings: {
            notSpecified: 'Не подключено ни одного источника',
            metrika: {
                title: 'Добавление аккаунта Яндекс.Метрики',
                header: '@:sources.settings.metrika.title',

                selectSources: 'Выберите источники данных',
                counter: 'Счетчтик Яндекс.Метрики',
                counterPlaceholder: 'Выберите счётчик',

                goalsNote: 'В статистику {appName} попадут только выбранные цели',
                selectGoals: 'Выберите цели',
                selectAllGoals: 'Выбрать всё',
                goalsAbsent: 'У вас еще нет целей в Яндекс.Метрике. Создайте их в <a class="link" target="_blank" href="https://metrika.yandex.ru/list">панели управления</a>, чтобы Daily Grow мог отслеживать конверсии',

                sendButton: 'Продолжить',
            },
            analytics: {
                title: 'Добавление аккаунта Google.Analytics',
                header: '@:sources.settings.analytics.title',

                selectAccount: 'Выберите аккаунт',
                account: 'Аккаунт',
                accountPlaceholder: 'Выберите аккаунт',

                selectApp: 'Выберите приложение',
                app: 'Приложение',
                appPlaceholder: 'Выберите приложение',

                selectCounter: 'Выберите счётчик',
                selectedCounter: 'Выбранный счётчик',
                counter: 'Счетчтик',
                counterPlaceholder: 'Выберите счётчик',

                selectGoals: 'Выберите цели',
                goalsNote: 'В статистику {appName} попадут только выбранные цели',
                goalsLoading: 'Загрузка целей...',
                selectAllGoals: 'Выбрать всё',
                goalsNotFound: 'Цели не найдены',

                sendButton: 'Продолжить',
            },
            vk: {
                title: 'Добавление аккаунта VK',
                header: '@:sources.settings.vk.title',

                selectAccount: 'Выберите аккаунт',
                account: 'Аккаунт',
                accountPlaceholder: 'Выберите аккаунт',

                selectClient: 'Выберите клиента',
                client: 'Клиент',
                clientsLoading: 'Загрузка клиентов...',
                clientsNotFound: 'Клиенты не найдены',

                selectCampaigns: 'Выберите кампании',
                campaignsNote: 'В статистику {appName} попадут только выбранные кампании',
                campaignsLoading: 'Загрузка кампаний...',
                selectAllCampaigns: 'Выбрать всё',
                campaignsNotFound: 'Кампании не найдены',

                sendButton: 'Продолжить',
            },
            adsense: {
                title: 'Добавление аккаунта Google Ads',
                header: '@:sources.settings.analytics.title',

                selectAccount: 'Выберите аккаунт',
                account: 'Аккаунт',
                accountPlaceholder: 'Выберите аккаунт',

                selectCampaigns: 'Выберите кампании',
                campaignsNote: 'В статистику {appName} попадут только выбранные кампании',
                campaignsLoading: 'Загрузка кампаний...',
                selectAllCampaigns: 'Выбрать всё',
                campaignsNotFound: 'Кампании не найдены',

                sendButton: 'Продолжить',
            },
            yandexDirect: {
                saveButton: 'Продолжить',
                campaignsNotFound: 'Кампании не найдены.',
                selectCampaigns: 'Выберите кампании',
                header: 'Настройки источника Яндекс.Директ',
                title: 'Настройки источника Яндекс.Директ'
            }
        },
    },
    period: {
        picker: {
            today: 'Сегодня',
            yesterday: 'Вчера',
            month: 'Месяц',

            '7days': '7 дней',
            '30days': '30 дней',
            '90days': '90 дней',
            'last-7days': 'Последние 7 дней',

            year: 'Этот год',

            'last-week': 'Прошлая неделя',
            'last-month': 'Прошлый месяц',

            customPeriod: 'Указать период',
            select: 'Выбрать',
        },
    },
    common: {
        devices: {
            tablet: 'Планшеты',
            mobile: 'Смартфоны',
            desktop: 'ПК',
            tv: 'TV',
        },
        planFeatures: {
            'analytics': 'Аналитика',
            'planfact': 'Отчёт план/факт',
            'smart-notifications': 'Умные уведомления',
            'audit': 'Ежедневные проверки рекламных кампаний в Яндекс Директ и Вконтакте',
            'sources-report': 'Ежемесячный отчет по всем каналам трафика',
        },
        paymentTypes: {
            payment: 'Банковской картой',
            invoice: 'Счёт для юридического лица',
        },
    },
    admin: {
        users: {
            editButton: 'Редактировать',
            header: {
                name: 'Имя',
                campaignsCount: 'Кол-во проектов',
                email: 'Email',
                phone: 'Телефон',
                controls: 'Управление',
                balance: 'Баланс',
                registration_date: 'Дата регистрации',
                last_visit_date: 'Дата последнего входа',
            },
            form: {
                phone: 'Телефон',
                name: 'Имя',
                password: 'Пароль',
                role: 'Роль',
                tariff: 'Тариф',
                saveButton: 'Сохранить'
            }
        },
        sidebar: {
            newUserButton: 'Добавить аккаунт',
            requests: 'Заявки'
        },
        plans: {
            statusNames: {
                active: 'Активный',
                archived: 'Архивный',
            },
            featureNames: {
                'analytics': 'Аналитика',
                'smart-notifications': 'Умные уведомления',

                'planfact': '[План/Факт] Отчет план/факт',
                'planfact-more-cabinets': '[План/Факт] Больше одного источника',

                'audit': '[Аудит] Аудит рекламы',
                'audit-links': '[Аудит] Проверка ссылок',

                'support-tg': '[Поддержка] Онлайн-чат в Telegram',
                'support-settings': '[Поддержка] Помощь в подключенни',
                'support-priority': '[Поддержка] Приоритетная поддрержка',
            },
            list: {
                btnEdit: 'Изменить',
                btnDelete: 'Удалить',
                btnCreate: 'Добавить тариф',
                valStatus: 'Статус:',
                valVisitsLimit: 'Максимальное число визитов:',
                valPrice: 'Стоимость:',
                title: 'Тарифы проектов',
                header: 'Тарифы проектов',
            },
            edit: {
                btnCancel: 'Отменить',
                btnSave: 'Сохранить тариф'
            },
            form: {
                phStatus: 'Выберите статус тарифа',
                phVisitsLimit: 'Максимальное число визитов',
                phPrice: 'Стоимость тарифа',
                phDescription: 'Описание тарифа',
                phName: 'Название тарифа'
            }
        },
    },
    users: {
        roles: {
            user: 'Пользователь',
            admin: 'Администратор',
            banned: 'Заблокированный',
        },
        tariffs: {
            free: 'Бесплатный',
            unlimited: 'Безлимитный',
        },
        new_payment: 'Новый платеж'
    },
    helpPage: {
        welcome: 'Добро пожаловать в Daily Grow',
        infoText: 'Отлично, вы сделали первый шаг для построения эффективной системы интернет-маркетинга и повышения продаж. Посмотрите наши обучающие видео, чтобы быстро подключить все каналы трафика и настроить аналитику.',
        infoAuthor: 'Денис Турушев, CEO Daily Grow',
        header: 'Начало работы',
        title: 'Поддержка',
        btns: {
            campaignSettings: 'Начать работу',
            settingsRequest: 'Заказать настройку'
        },
        settingsRequest: {
            header: 'Заказ настройки проекта',
            infoText: 'Специалист по внедрению подключит и настроит проект в Daily Grow, а веб-аналитик будет сопровождать вас в течение месяца.',
            form: {
                name: 'Имя',
                phone: 'Телефон',
                submit: 'Заказать настройку',
                close: 'Отмена',
                campaign: 'Выберите проект для настройки'
            }
        }
    },
    checks: {
        group: {
            names: {
                accounts: 'Аккаунт',
                campaigns: 'Кампании',
                adgroups: 'Группы объявлений',
                ads: 'Объявления',
            },
            failsCounter: '{failed} из {total}',
            fails: 'замечаний',
            noFails: 'замечаний нет',
        },
        filters: {
            'yandex-direct': {
                states: {
                    options: {
                        SUSPENDED: 'Приостановлена',
                        ENDED: 'Закончилась',
                        ON: 'Работает',
                        OFF: 'Остановлена',
                    },
                    label: 'Состояние',
                    all: 'Любое',
                },
                statuses: {
                    options: {
                        DRAFT: 'Черновик',
                        MODERATION: 'На модерации',
                        ACCEPTED: 'Прошла модерацию',
                        REJECTED: 'Отклонена модерацией',
                    },
                    label: 'Статус',
                    all: 'Любой',
                },
            },
            'vk': {
                statuses: {
                    options: {
                        0: 'Остановлена',
                        1: 'Запущена',
                        2: 'Удалена',
                    },
                    label: 'Статус',
                    all: 'Любой',
                },
            },
        },
    },
    analytics: {
        'cabinet-item-type': {
            account: 'Аккаунт',
            campaign: 'Кампания',
            ad_group: 'Группа объявлений',
            ad: 'Объявление',
        }
    },
    'seo-audit': seoAudit,
};
