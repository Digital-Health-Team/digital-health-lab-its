export const ENV = {
    isProduction: import.meta.env.PROD,
    isDevelopment: import.meta.env.DEV,
    debug: import.meta.env.APP_DEBUG === "true" || import.meta.env.DEBUG === "true",
    
    // APP
    appName: import.meta.env.APP_NAME || import.meta.env.VITE_APP_NAME || "Web Publikasi ITS",
    appEnv: import.meta.env.APP_ENV || "local",
    appKey: import.meta.env.APP_KEY || "",
    appUrl: import.meta.env.APP_URL || "http://localhost",
    appLocale: import.meta.env.APP_LOCALE || "en",
    appFallbackLocale: import.meta.env.APP_FALLBACK_LOCALE || "en",
    appFakerLocale: import.meta.env.APP_FAKER_LOCALE || "en_US",
    appMaintenanceDriver: import.meta.env.APP_MAINTENANCE_DRIVER || "file",
    
    bcryptRounds: Number(import.meta.env.BCRYPT_ROUNDS) || 12,
    
    // LOG
    logChannel: import.meta.env.LOG_CHANNEL || "stack",
    logStack: import.meta.env.LOG_STACK || "single",
    logDeprecationsChannel: import.meta.env.LOG_DEPRECATIONS_CHANNEL || "null",
    logLevel: import.meta.env.LOG_LEVEL || "debug",

    // DB
    dbConnection: import.meta.env.DB_CONNECTION || "mysql",
    dbHost: import.meta.env.DB_HOST || "127.0.0.1",
    dbPort: import.meta.env.DB_PORT || "3306",
    dbDatabase: import.meta.env.DB_DATABASE || "publikasi_its_db",
    dbUsername: import.meta.env.DB_USERNAME || "root",
    dbPassword: import.meta.env.DB_PASSWORD || "",

    // SESSION
    sessionDriver: import.meta.env.SESSION_DRIVER || "database",
    sessionLifetime: Number(import.meta.env.SESSION_LIFETIME) || 120,
    sessionEncrypt: import.meta.env.SESSION_ENCRYPT === "true",
    sessionPath: import.meta.env.SESSION_PATH || "/",
    sessionDomain: import.meta.env.SESSION_DOMAIN || "null",

    broadcastConnection: import.meta.env.BROADCAST_CONNECTION || "log",
    filesystemDisk: import.meta.env.FILESYSTEM_DISK || "local",
    queueConnection: import.meta.env.QUEUE_CONNECTION || "database",
    cacheStore: import.meta.env.CACHE_STORE || "database",
    
    memcachedHost: import.meta.env.MEMCACHED_HOST || "127.0.0.1",

    // REDIS
    redisClient: import.meta.env.REDIS_CLIENT || "phpredis",
    redisHost: import.meta.env.REDIS_HOST || "127.0.0.1",
    redisPassword: import.meta.env.REDIS_PASSWORD || "null",
    redisPort: import.meta.env.REDIS_PORT || "6379",

    // MAIL
    mailMailer: import.meta.env.MAIL_MAILER || "log",
    mailScheme: import.meta.env.MAIL_SCHEME || "null",
    mailHost: import.meta.env.MAIL_HOST || "127.0.0.1",
    mailPort: import.meta.env.MAIL_PORT || "2525",
    mailUsername: import.meta.env.MAIL_USERNAME || "null",
    mailPassword: import.meta.env.MAIL_PASSWORD || "null",
    mailFromAddress: import.meta.env.MAIL_FROM_ADDRESS || "hello@example.com",
    mailFromName: import.meta.env.MAIL_FROM_NAME || "${APP_NAME}",

    // AWS
    awsAccessKeyId: import.meta.env.AWS_ACCESS_KEY_ID || "",
    awsSecretAccessKey: import.meta.env.AWS_SECRET_ACCESS_KEY || "",
    awsDefaultRegion: import.meta.env.AWS_DEFAULT_REGION || "us-east-1",
    awsBucket: import.meta.env.AWS_BUCKET || "",
    awsUsePathStyleEndpoint: import.meta.env.AWS_USE_PATH_STYLE_ENDPOINT === "true",

    apiBaseUrl: import.meta.env.VITE_API_BASE_URL || "",
};
