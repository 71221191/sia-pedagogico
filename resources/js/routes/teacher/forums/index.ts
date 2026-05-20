import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\LearningForumController::index
 * @see app/Http/Controllers/Teacher/LearningForumController.php:13
 * @route '/docente/unidades/{unit}/foros'
 */
export const index = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/docente/unidades/{unit}/foros',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::index
 * @see app/Http/Controllers/Teacher/LearningForumController.php:13
 * @route '/docente/unidades/{unit}/foros'
 */
index.url = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { unit: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { unit: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    unit: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        unit: typeof args.unit === 'object'
                ? args.unit.id
                : args.unit,
                }

    return index.definition.url
            .replace('{unit}', parsedArgs.unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::index
 * @see app/Http/Controllers/Teacher/LearningForumController.php:13
 * @route '/docente/unidades/{unit}/foros'
 */
index.get = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Teacher\LearningForumController::index
 * @see app/Http/Controllers/Teacher/LearningForumController.php:13
 * @route '/docente/unidades/{unit}/foros'
 */
index.head = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningForumController::index
 * @see app/Http/Controllers/Teacher/LearningForumController.php:13
 * @route '/docente/unidades/{unit}/foros'
 */
    const indexForm = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningForumController::index
 * @see app/Http/Controllers/Teacher/LearningForumController.php:13
 * @route '/docente/unidades/{unit}/foros'
 */
        indexForm.get = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Teacher\LearningForumController::index
 * @see app/Http/Controllers/Teacher/LearningForumController.php:13
 * @route '/docente/unidades/{unit}/foros'
 */
        indexForm.head = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Teacher\LearningForumController::store
 * @see app/Http/Controllers/Teacher/LearningForumController.php:24
 * @route '/docente/unidades/{unit}/foros'
 */
export const store = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/docente/unidades/{unit}/foros',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::store
 * @see app/Http/Controllers/Teacher/LearningForumController.php:24
 * @route '/docente/unidades/{unit}/foros'
 */
store.url = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { unit: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { unit: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    unit: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        unit: typeof args.unit === 'object'
                ? args.unit.id
                : args.unit,
                }

    return store.definition.url
            .replace('{unit}', parsedArgs.unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::store
 * @see app/Http/Controllers/Teacher/LearningForumController.php:24
 * @route '/docente/unidades/{unit}/foros'
 */
store.post = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningForumController::store
 * @see app/Http/Controllers/Teacher/LearningForumController.php:24
 * @route '/docente/unidades/{unit}/foros'
 */
    const storeForm = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningForumController::store
 * @see app/Http/Controllers/Teacher/LearningForumController.php:24
 * @route '/docente/unidades/{unit}/foros'
 */
        storeForm.post = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Teacher\LearningForumController::toggle
 * @see app/Http/Controllers/Teacher/LearningForumController.php:36
 * @route '/docente/foros/{forum}/toggle'
 */
export const toggle = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggle.url(args, options),
    method: 'patch',
})

toggle.definition = {
    methods: ["patch"],
    url: '/docente/foros/{forum}/toggle',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::toggle
 * @see app/Http/Controllers/Teacher/LearningForumController.php:36
 * @route '/docente/foros/{forum}/toggle'
 */
toggle.url = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return toggle.definition.url
            .replace('{forum}', parsedArgs.forum.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::toggle
 * @see app/Http/Controllers/Teacher/LearningForumController.php:36
 * @route '/docente/foros/{forum}/toggle'
 */
toggle.patch = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggle.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningForumController::toggle
 * @see app/Http/Controllers/Teacher/LearningForumController.php:36
 * @route '/docente/foros/{forum}/toggle'
 */
    const toggleForm = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggle.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningForumController::toggle
 * @see app/Http/Controllers/Teacher/LearningForumController.php:36
 * @route '/docente/foros/{forum}/toggle'
 */
        toggleForm.patch = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: toggle.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    toggle.form = toggleForm
/**
* @see \App\Http\Controllers\Teacher\LearningForumController::destroy
 * @see app/Http/Controllers/Teacher/LearningForumController.php:42
 * @route '/docente/foros/{forum}'
 */
export const destroy = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/docente/foros/{forum}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::destroy
 * @see app/Http/Controllers/Teacher/LearningForumController.php:42
 * @route '/docente/foros/{forum}'
 */
destroy.url = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{forum}', parsedArgs.forum.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\LearningForumController::destroy
 * @see app/Http/Controllers/Teacher/LearningForumController.php:42
 * @route '/docente/foros/{forum}'
 */
destroy.delete = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningForumController::destroy
 * @see app/Http/Controllers/Teacher/LearningForumController.php:42
 * @route '/docente/foros/{forum}'
 */
    const destroyForm = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningForumController::destroy
 * @see app/Http/Controllers/Teacher/LearningForumController.php:42
 * @route '/docente/foros/{forum}'
 */
        destroyForm.delete = (args: { forum: number | { id: number } } | [forum: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const forums = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
toggle: Object.assign(toggle, toggle),
destroy: Object.assign(destroy, destroy),
}

export default forums