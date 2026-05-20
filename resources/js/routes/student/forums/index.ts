import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\ForumController::show
 * @see app/Http/Controllers/ForumController.php:16
 * @route '/foro/{forum}'
 */
export const show = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/foro/{forum}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\ForumController::show
 * @see app/Http/Controllers/ForumController.php:16
 * @route '/foro/{forum}'
 */
show.url = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { forum: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { forum: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    forum: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        forum: typeof args.forum === 'object'
                ? args.forum.id
                : args.forum,
                }

    return show.definition.url
            .replace('{forum}', parsedArgs.forum.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ForumController::show
 * @see app/Http/Controllers/ForumController.php:16
 * @route '/foro/{forum}'
 */
show.get = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\ForumController::show
 * @see app/Http/Controllers/ForumController.php:16
 * @route '/foro/{forum}'
 */
show.head = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\ForumController::show
 * @see app/Http/Controllers/ForumController.php:16
 * @route '/foro/{forum}'
 */
    const showForm = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\ForumController::show
 * @see app/Http/Controllers/ForumController.php:16
 * @route '/foro/{forum}'
 */
        showForm.get = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\ForumController::show
 * @see app/Http/Controllers/ForumController.php:16
 * @route '/foro/{forum}'
 */
        showForm.head = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Http\Controllers\ForumController::storePost
 * @see app/Http/Controllers/ForumController.php:37
 * @route '/foro/{forum}/comentar'
 */
export const storePost = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storePost.url(args, options),
    method: 'post',
})

storePost.definition = {
    methods: ["post"],
    url: '/foro/{forum}/comentar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\ForumController::storePost
 * @see app/Http/Controllers/ForumController.php:37
 * @route '/foro/{forum}/comentar'
 */
storePost.url = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { forum: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { forum: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    forum: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        forum: typeof args.forum === 'object'
                ? args.forum.id
                : args.forum,
                }

    return storePost.definition.url
            .replace('{forum}', parsedArgs.forum.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\ForumController::storePost
 * @see app/Http/Controllers/ForumController.php:37
 * @route '/foro/{forum}/comentar'
 */
storePost.post = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storePost.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\ForumController::storePost
 * @see app/Http/Controllers/ForumController.php:37
 * @route '/foro/{forum}/comentar'
 */
    const storePostForm = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: storePost.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\ForumController::storePost
 * @see app/Http/Controllers/ForumController.php:37
 * @route '/foro/{forum}/comentar'
 */
        storePostForm.post = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: storePost.url(args, options),
            method: 'post',
        })
    
    storePost.form = storePostForm
const forums = {
    show: Object.assign(show, show),
storePost: Object.assign(storePost, storePost),
}

export default forums