module.exports = {
    title: 'Twitter 2 Documentation',
    description: 'Twitter 2 Documentation',
    base: '/twitter/v2/',
    ga: 'UA-1547168-34',
    themeConfig: {
        docsRepo: 'dukt/twitter',
        docsDir: 'docs',
        docsBranch: 'v2-docs',
        editLinks: true,
        editLinkText: 'Edit this page on GitHub',
        nav: [
            { text: 'Analytics', link: 'https://docs.dukt.net/analytics/v4/'},
            { text: 'Facebook', link: 'https://docs.dukt.net/facebook/v2/'},
            { text: 'Social', link: 'https://docs.dukt.net/social/v2/'},
            { text: 'Twitter', link: '/'},
            { text: 'Videos', link: 'https://docs.dukt.net/videos/v2/'},
        ],
        sidebar: {
            '/': [
                {
                    title: 'Getting Started',
                    collapsable: false,
                    children: [
                        '',
                        'requirements',
                        'installation',
                        'updating',
                        'connect-twitter',
                        'configuration',
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
                        'demo',
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
