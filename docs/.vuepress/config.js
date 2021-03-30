module.exports = {
    title: 'Twitter Documentation',
    description: 'Twitter Documentation',
    base: '/docs/twitter/v2/',
    plugins: {
        '@vuepress/google-analytics': {
            'ga': 'UA-1547168-20'
        },
        'sitemap': {
            hostname: 'https://dukt.net/docs/twitter/v2/'
        },
    },
    theme: 'default-prefers-color-scheme',
    themeConfig: {
        docsRepo: 'dukt/twitter',
        docsDir: 'docs',
        docsBranch: 'v2-docs',
        editLinks: true,
        editLinkText: 'Edit this page on GitHub',
        lastUpdated: 'Last Updated',
        sidebar: {
            '/': [
                {
                    title: 'Twitter plugin for Craft CMS',
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
