# Analytics Docs

## Analytics Tracking

## Analytics Real-Time Widget

## Analytics Chart Widget

## Changelog

### Services

#### Before

AnalyticsService::sendRequest(Analytics_RequestCriteriaModel $criteria)
AnalyticsService::saveToken(Oauth_TokenModel $token)
AnalyticsService::getToken()
AnalyticsService::deleteToken()
AnalyticsService::getElementUrlPath($elementId, $localeId)
AnalyticsService::getProfile()
AnalyticsService::getWebProperty()
AnalyticsService::getPropertiesOpts()
AnalyticsService::getSetting($key)
AnalyticsService::getDimMet($key)
AnalyticsService::getBrowserSections($json = false)
AnalyticsService::getBrowserData($json = false)
AnalyticsService::getBrowserSelect()
AnalyticsService::getLanguage()
AnalyticsService::getContinentCode($label)
AnalyticsService::getSubContinentCode($label)

#### After

Analytics_CacheService
Analytics_MetaService
Analytics_PluginService

AnalyticsService::getRealtimeRefreshInterval()
AnalyticsService::getDataSource($className = 'GoogleAnalytics')
AnalyticsService::getProfileId()
AnalyticsService::track($options)
AnalyticsService::sendRequest(Analytics_RequestCriteriaModel $criteria)
AnalyticsService::getElementUrlPath($elementId, $localeId)

Analytics_ApiService::getProfiles($webProperty)
Analytics_ApiService::getWebProperties()
Analytics_ApiService::getWebProperty($webPropertyId)
Analytics_ApiService::apiGetGAData($ids, $startDate, $endDate, $metrics, $optParams = array(), $enableCache = true)
Analytics_ApiService::apiGetGADataRealtime($ids, $metrics, $optParams = array())
Analytics_ApiService::getDataRealtime()
Analytics_ApiService::getDataGa()
Analytics_ApiService::getManagementWebproperties()
Analytics_ApiService::getManagementProfiles()
Analytics_ApiService::getMetadataColumns()

Analytics_OauthService::requireOauth()
Analytics_OauthService::saveToken(Oauth_TokenModel $token)
Analytics_OauthService::getToken()
Analytics_OauthService::deleteToken()

### Variable

#### Before

api($attributes = null)
getToken()
getWebProperty()
getProfile()
isConfigured()

#### After

track($options = null)
api($attributes = null)
getToken()
isConfigured()