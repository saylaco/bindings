## Formats
```
[
    <alias:string> => [
        <abstractName:string>,
        [resolverCallback:Closure],
        [bootCallback:Closure],
        [isSingleton:bool]
    ]
]
```
 resolver only
 ```
 [
    'bookRepo' => [
        BookRepo::class,
        function($container){ 
            return new BookRepo(); 
        }
    ],
    'bookFactory' => [
        BookFactory::class,
        function($container){ 
            return new BookFactory($container->get(BookRepo::class)); 
        }
    ]
]
```
 resolver and booter 
 ```
 [
    'bookFactory' => [
        BookFactory::class,
        function($container){ 
            return new BookFactory(); 
        },
        function($container) {
            $bookFactory = $container->get(BookFactory::class); 
            $bookFactory->enableAuthorLastNameValidation(); 
        }
    ]
]
```
 resolver and booter using qualified alias 
 ```
 [
    'bookFactory' => [
        BookFactory::class,
        function($container){ 
            return new BookFactory(); 
        },
        function($container, string $qualifiedAlias) {
            $bookFactory = $container->get($qualifiedAlias); 
            $bookFactory->enableAuthorLastNameValidation(); 
        }
    ]
]
```