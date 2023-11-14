export default {
    auth: {
        login: {
            metaTitle: 'Sign in',

            title: 'Sign in {appName}',
            fields: {
                email: 'Email',
                pass: 'Password',
            },
            login: 'Sign in',
            byGoogle: '@:auth.login.login with Google',
            register: 'Sign up',
            forgotPass: 'Forgot password?',
        },
        reg: {
            metaTitle: 'Sign up',

            title: 'Sign up {appName}',
            fields: {
                email: 'Email',
                phone: 'Phone',
                pass: 'Password',
                name: 'Your name',
            },
            register: 'Sign up',
            byGoogle: '@:auth.reg.register with Google',
            agreement: 'By sending information through the electronic form, you agree to the terms of the offer and consent to the processing of personal data on the terms of the Policy',

            loginLink: {
                prefix: 'Already have an account? ',
                linkText: 'Login',
            },
        },
        forgot_pass: {
            title: 'Forgot your password?',
            header: '@:auth.forgot_pass.title',
            form: {
                email: 'Email',
            },
            send: 'Send password reset link',
            text: 'Forgot your password? ' +
                'Enter the email address you provided during registration, ' +
                'Enter the email address you provided during registration, ' +
                'by clicking on which you can enter a new password.'
        },
        reg_finish: {
            title: 'Completion of registration',
            header: 'Sign up {appName}',
            form: {
                phone: 'Phone',
                pass: 'Password',
            },
            finish_register: 'Complete registration',
        },
        reset_pass: {
            title: 'Reset password',
            header: '@:auth.reset_pass.title',
            form: {
                new_pass: 'New password',
                confirm_pass: 'Password confirmation',
            },
            reset_pass: 'Reset password',
        },
        verify_email: {
            title: 'E-Mail Verification',
            header: '@:auth.verify_email.title',
            send_again: 'Send email again',
            send_again_msg: 'A new email with a confirmation link has been sent to the email address you provided during registration.',
            logout: 'Sign out',
            text: 'Thank you for registering! ' +
                'Please verify your email address before you start. ' +
                'To do this, follow the link in the letter sent to the specified address.',
        },
    },
    header: {
        menu: {
            account: 'Account',
            users: 'Users',
            balance: 'Payment and balance',
            notifications: 'Notifications',
            integration: 'Integration',
        },
        notifications: {
            new: 'New',
            integrations: 'Integrations',
        },
    },
    sidebar: {
        campaigns: {
            edit: 'Edit',
        },
        menu: {
            browse: 'Browse',
            analytics: 'Analytics',
            checks: 'Check',
        },
    },
    campaigns: {
        settings: {
            title: 'Campaign settings',
            header: 'Settings',
            buttons: {
                delete: 'Delete campaign',
                edit: 'Edit campaign',
                sources: 'Edit',
                create: 'Create campaign',
            },
            emptyText: 'You have not added any campaigns yet.',

            create: {
                title: 'Adding a Campaign',
                header: 'Add Campaign',
                placeholder: 'Name',
                button: 'Add',
            },
            delete: {
                title: 'Deleting a Campaign',
                header: 'Delete Campaign',
                button: 'Delete',
            },
            edit: {
                title: 'Campaign editing',
                header: 'Edit Campaign',
                placeholder: 'Name',
                button: 'Edit',
            },
        },
        feed: {
            new_users: {
                title: 'New users',
                name: 'The number of new users',
                text: {
                    down: '@:campaigns.feed.new_users.name decreased by <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.new_users.name increased by <span class="font-bold">{number}%</span>',
                },
            },
            avg_visit_duration: {
                title: 'Visit duration',
                name: 'The average session duration',
                text: {
                    down: '@:campaigns.feed.avg_visit_duration.name decreased by <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.avg_visit_duration.name increased by <span class="font-bold">{number}%</span>',
                },
            },
            page_views: {
                title: 'Page views',
                name: 'Page views',
                text: {
                    down: '@:campaigns.feed.page_views.name decreased by <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.page_views.name increased by <span class="font-bold">{number}%</span>',
                },
            },
            bounce_rate: {
                title: 'Bounces',
                name: 'The bounce rate',
                text: {
                    down: '@:campaigns.feed.bounce_rate.name decreased by <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.bounce_rate.name increased by <span class="font-bold">{number}%</span>',
                },
            },
            reaches: {
                title: 'Reaches',
                name: 'The number of requests',
                text: {
                    down: '@:campaigns.feed.reaches.name decreased by <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.reaches.name increased by <span class="font-bold">{number}%</span>',
                },
            },
            conversion_rate: {
                title: 'Conversions',
                name: 'Website conversion rate',
                text: {
                    down: '@:campaigns.feed.conversion_rate.name decreased by <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.conversion_rate.name increased by <span class="font-bold">{number}%</span>',
                },
            },
            mobile_traffic: {
                title: 'Mobile traffic',
                name: 'Mobile traffic',
                text: {
                    down: '@:campaigns.feed.mobile_traffic.name decreased by <span class="font-bold">{number}%</span>',
                    up: '@:campaigns.feed.mobile_traffic.name increased by <span class="font-bold">{number}%</span>',
                },
            },
            depth: {
                title: 'Depth',
                name: 'Depth',
                text: {
                    down: 'The number of pages viewed by the visitor decreased by <span class="font-bold">{number}%</span>',
                    up: 'The number of pages viewed by the visitor increased by <span class="font-bold">{number}%</span>',
                },
            },
            devices: {
                title: 'Traffic by devices',
            },
            city_conversion_rate: {
                title: 'Top 5 regions by reach conversion',
            },
            city_reaches: {
                title: 'Top 5 regions by number of reaches',
            },
            convsNotLoaded: 'Data not loaded...',

            refresh: 'Refresh',
            refreshNotAvailable: 'Not available',
            refreshLoading: 'Loading...',
            nextRefreshAt: 'You can manually update the data no more than once every {freq} hours, next refresh at {at}.',

            deviceFooter: '{prePeriod} compared to {selPeriod}',
            footer:
                '{prePeriod} <span class="text-primary">({preConv})</span> ' +
                'compared to {selPeriod} <span class="text-primary">({selConv})</span>',
        },
        planfact: {
            fields: {
                expenses: 'Expenses',
                income: 'Income',
                requests: 'Requests',
                clicks: 'Clicks',
                cpl: 'CPL',
                cpc: 'CPC',
                cr: 'CR',
                drr: 'CRR',
            },
            settings: {
                add: {
                    title: 'Adding an advertising channel',
                    fieldPlaceholder: 'Enter the planned value',
                    period: 'Period',
                    source: 'Advertising channel',
                    sourcePlaceholder: 'Select advertising channel',
                    allSources: 'All channels',
                    utmCampaign: 'UTM Campaign',
                    utmCampaignPlaceholder: 'Enter campaign UTM tag',

                    btn: {
                        add: 'Add',
                        cancel: 'Cancel',
                    },
                },
                table: {
                    summary: 'Total',
                },
            },
            block: {
                filters: {
                    source: {
                        label: 'Source',
                        all: 'All',
                    },
                    device: {
                        label: 'Device',
                        all: 'All',
                    },
                    domain: {
                        label: 'Domain',
                        all: 'All',
                    },
                    campaign: {
                        label: 'UTM Campaign',
                        all: 'Any',
                    },
                },
                title: 'Plan/Fact',
                hide: 'Hide',
                more: 'More',
                settings: 'Settings',
                'paginator-of': 'of',
                charts: {
                    'plan-label': 'Plan',
                },
            },
        },
    },
    sources: {
        names: {
            'yandex-direct': 'Yandex.Metrika',
            'yandex-metrika': 'Yandex.Direct',
            'google-ads': 'Google Ads',
            'google-analytics': 'Google Analytics',
            vk: 'ВКонтакте',
            fb: 'Facebook',
        },
        list: {
            title: 'Sources',
            connect: 'Connect',
            connected: 'Connected',
            notConnected: 'Not connected',
        },
        settings: {
            metrika: {
                title: 'Adding a Yandex.Metrika account',
                header: '@:sources.settings.metrika.title',

                selectSources: 'Select Data Sources',
                counter: 'Yandex.Metrika counter',
                counterPlaceholder: 'Choose counter',

                goalsNote: 'Only the selected targets will be included in the {appName} stats',
                selectGoals: 'Select goals',
                selectAllGoals: 'Select all',
                goalsAbsent: 'You don\'t have goals in Yandex.Metrika yet. Create them in your dashboard so Daily Grow can track conversions',

                sendButton: 'Continue',
            },
            analytics: {
                title: 'Adding a Google Analytics account',
                header: '@:sources.settings.analytics.title',

                selectAccount: 'Choose an account',
                account: 'Account',
                accountPlaceholder: 'Choose an account',

                selectApp: 'Select application',
                app: 'Application',
                appPlaceholder: 'Select application',

                selectCounter: 'Choose counter',
                selectedCounter: 'Selected counter',
                counter: 'Counter',
                counterPlaceholder: 'Choose counter',

                selectGoals: 'Select goals',
                goalsNote: 'Only the selected goals will be included in the {appName} stats',
                goalsLoading: 'Goals loading...',
                selectAllGoals: 'Select all',
                goalsNotFound: 'Goals not found',

                sendButton: 'Continue',
            },
            adsense: {
                title: 'Adding a Google Ads account',
                header: '@:sources.settings.analytics.title',

                selectAccount: 'Choose an account',
                account: 'Account',
                accountPlaceholder: 'Choose an account',

                selectCampaigns: 'Choose campaigns',
                campaignsNote: 'Only the selected campaigns will be included in the {appName} stats',
                campaignsLoading: 'Campaigns loading...',
                selectAllCampaigns: 'Select all',
                campaignsNotFound: 'Campaigns now found',

                sendButton: 'Continue',
            },
        },
    },
    period: {
        picker: {
            today: 'Today',
            yesterday: 'Yesterday',
            month: 'Month',

            '7days': '7 days',
            '30days': '30 days',
            last7days: 'Last 7 days',

            thisMonth: 'This month',
            thisYear: 'This year',

            prevWeek: 'Previous weeks',
            prevMonth: 'Previous month',

            customPeriod: 'Custom period',
            select: 'Select',
        },
    },
    common: {
        devices: {
            tablet: 'Tablets',
            mobile: 'Smartphones',
            desktop: 'PC',
            tv: 'TV',
        },
    },
};
