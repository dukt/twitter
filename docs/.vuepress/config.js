module.exports = {
    title: 'Twitter 2 Documentation',
    description: 'Twitter 2 Documentation',
    base: '/twitter/v2/',
    ga: 'UA-1547168-34',
    themeConfig: {
        sidebar: {
            '/': [
                {
                    title: 'Getting Started',
                    collapsable: false,
                    children: [
                        '',
                        'requirements',
                        'installation',
                        'connect-twitter',
                    ]
                },
                {
                    title: 'Fields',
                    collapsable: false,
                    children: [
                        'tweet-field',
                    ]
                },
                {
                    title: 'Widgets',
                    collapsable: false,
                    children: [
                        'search-widget',
                    ]
                },
                {
                    title: 'Models',
                    collapsable: false,
                    children: [
                        'tweet-model',
                    ]
                },
                {
                    title: 'Templating',
                    collapsable: false,
                    children: [
                        'craft-twitter',
                        'twig-filters',
                        'twig-functions',
                    ]
                },
                {
                    title: 'Advanced Topics',
                    collapsable: false,
                    children: [
                        'request-api',
                        'ajax-api-request',
                    ]
                }
            ],
        }
    }
}