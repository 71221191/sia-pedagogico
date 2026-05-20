import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::index
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:17
 * @route '/docente/unidades/{unit}/recursos'
 */
export const index = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/docente/unidades/{unit}/recursos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::index
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:17
 * @route '/docente/unidades/{unit}/recursos'
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
* @see \App\Http\Controllers\Teacher\LearningResourceController::index
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:17
 * @route '/docente/unidades/{unit}/recursos'
 */
index.get = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::index
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:17
 * @route '/docente/unidades/{unit}/recursos'
 */
index.head = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::index
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:17
 * @route '/docente/unidades/{unit}/recursos'
 */
    const indexForm = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::index
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:17
 * @route '/docente/unidades/{unit}/recursos'
 */
        indexForm.get = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::index
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:17
 * @route '/docente/unidades/{unit}/recursos'
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
* @see \App\Http\Controllers\Teacher\LearningResourceController::store
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:32
 * @route '/docente/unidades/{unit}/recursos'
 */
export const store = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/docente/unidades/{unit}/recursos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::store
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:32
 * @route '/docente/unidades/{unit}/recursos'
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
* @see \App\Http\Controllers\Teacher\LearningResourceController::store
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:32
 * @route '/docente/unidades/{unit}/recursos'
 */
store.post = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::store
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:32
 * @route '/docente/unidades/{unit}/recursos'
 */
    const storeForm = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::store
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:32
 * @route '/docente/unidades/{unit}/recursos'
 */
        storeForm.post = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::toggle
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:65
 * @route '/docente/recursos/{resource}/toggle'
 */
export const toggle = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggle.url(args, options),
    method: 'patch',
})

toggle.definition = {
    methods: ["patch"],
    url: '/docente/recursos/{resource}/toggle',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::toggle
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:65
 * @route '/docente/recursos/{resource}/toggle'
 */
toggle.url = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { resource: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    resource: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        resource: typeof args.resource === 'object'
                ? args.resource.id
                : args.resource,
                }

    return toggle.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::toggle
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:65
 * @route '/docente/recursos/{resource}/toggle'
 */
toggle.patch = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggle.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::toggle
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:65
 * @route '/docente/recursos/{resource}/toggle'
 */
    const toggleForm = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggle.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::toggle
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:65
 * @route '/docente/recursos/{resource}/toggle'
 */
        toggleForm.patch = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Teacher\LearningResourceController::destroy
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:74
 * @route '/docente/recursos/{resource}'
 */
export const destroy = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/docente/recursos/{resource}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::destroy
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:74
 * @route '/docente/recursos/{resource}'
 */
destroy.url = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { resource: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { resource: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    resource: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        resource: typeof args.resource === 'object'
                ? args.resource.id
                : args.resource,
                }

    return destroy.definition.url
            .replace('{resource}', parsedArgs.resource.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\LearningResourceController::destroy
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:74
 * @route '/docente/recursos/{resource}'
 */
destroy.delete = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::destroy
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:74
 * @route '/docente/recursos/{resource}'
 */
    const destroyForm = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\LearningResourceController::destroy
 * @see app/Http/Controllers/Teacher/LearningResourceController.php:74
 * @route '/docente/recursos/{resource}'
 */
        destroyForm.delete = (args: { resource: number | { id: number } } | [resource: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const resources = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
toggle: Object.assign(toggle, toggle),
destroy: Object.assign(destroy, destroy),
}

export default resources