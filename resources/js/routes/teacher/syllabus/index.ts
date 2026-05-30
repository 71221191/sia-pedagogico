import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\SyllabusController::index
 * @see app/Http/Controllers/Teacher/SyllabusController.php:16
 * @route '/docente/silabo/seccion/{section}'
 */
export const index = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/docente/silabo/seccion/{section}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\SyllabusController::index
 * @see app/Http/Controllers/Teacher/SyllabusController.php:16
 * @route '/docente/silabo/seccion/{section}'
 */
index.url = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { section: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { section: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    section: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        section: typeof args.section === 'object'
                ? args.section.id
                : args.section,
                }

    return index.definition.url
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\SyllabusController::index
 * @see app/Http/Controllers/Teacher/SyllabusController.php:16
 * @route '/docente/silabo/seccion/{section}'
 */
index.get = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Teacher\SyllabusController::index
 * @see app/Http/Controllers/Teacher/SyllabusController.php:16
 * @route '/docente/silabo/seccion/{section}'
 */
index.head = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Teacher\SyllabusController::index
 * @see app/Http/Controllers/Teacher/SyllabusController.php:16
 * @route '/docente/silabo/seccion/{section}'
 */
    const indexForm = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Teacher\SyllabusController::index
 * @see app/Http/Controllers/Teacher/SyllabusController.php:16
 * @route '/docente/silabo/seccion/{section}'
 */
        indexForm.get = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Teacher\SyllabusController::index
 * @see app/Http/Controllers/Teacher/SyllabusController.php:16
 * @route '/docente/silabo/seccion/{section}'
 */
        indexForm.head = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Teacher\SyllabusController::store
 * @see app/Http/Controllers/Teacher/SyllabusController.php:28
 * @route '/docente/silabo/seccion/{section}/subir'
 */
export const store = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/docente/silabo/seccion/{section}/subir',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\SyllabusController::store
 * @see app/Http/Controllers/Teacher/SyllabusController.php:28
 * @route '/docente/silabo/seccion/{section}/subir'
 */
store.url = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { section: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { section: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    section: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        section: typeof args.section === 'object'
                ? args.section.id
                : args.section,
                }

    return store.definition.url
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\SyllabusController::store
 * @see app/Http/Controllers/Teacher/SyllabusController.php:28
 * @route '/docente/silabo/seccion/{section}/subir'
 */
store.post = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Teacher\SyllabusController::store
 * @see app/Http/Controllers/Teacher/SyllabusController.php:28
 * @route '/docente/silabo/seccion/{section}/subir'
 */
    const storeForm = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\SyllabusController::store
 * @see app/Http/Controllers/Teacher/SyllabusController.php:28
 * @route '/docente/silabo/seccion/{section}/subir'
 */
        storeForm.post = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Teacher\SyllabusController::destroy
 * @see app/Http/Controllers/Teacher/SyllabusController.php:58
 * @route '/docente/silabo/seccion/{section}/eliminar'
 */
export const destroy = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/docente/silabo/seccion/{section}/eliminar',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Teacher\SyllabusController::destroy
 * @see app/Http/Controllers/Teacher/SyllabusController.php:58
 * @route '/docente/silabo/seccion/{section}/eliminar'
 */
destroy.url = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { section: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { section: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    section: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        section: typeof args.section === 'object'
                ? args.section.id
                : args.section,
                }

    return destroy.definition.url
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\SyllabusController::destroy
 * @see app/Http/Controllers/Teacher/SyllabusController.php:58
 * @route '/docente/silabo/seccion/{section}/eliminar'
 */
destroy.delete = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Teacher\SyllabusController::destroy
 * @see app/Http/Controllers/Teacher/SyllabusController.php:58
 * @route '/docente/silabo/seccion/{section}/eliminar'
 */
    const destroyForm = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\SyllabusController::destroy
 * @see app/Http/Controllers/Teacher/SyllabusController.php:58
 * @route '/docente/silabo/seccion/{section}/eliminar'
 */
        destroyForm.delete = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const syllabus = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
destroy: Object.assign(destroy, destroy),
}

export default syllabus