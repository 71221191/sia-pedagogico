import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\AvailabilityController::index
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:15
 * @route '/docente/mi-disponibilidad'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/docente/mi-disponibilidad',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AvailabilityController::index
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:15
 * @route '/docente/mi-disponibilidad'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AvailabilityController::index
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:15
 * @route '/docente/mi-disponibilidad'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Teacher\AvailabilityController::index
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:15
 * @route '/docente/mi-disponibilidad'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Teacher\AvailabilityController::index
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:15
 * @route '/docente/mi-disponibilidad'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Teacher\AvailabilityController::index
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:15
 * @route '/docente/mi-disponibilidad'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Teacher\AvailabilityController::index
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:15
 * @route '/docente/mi-disponibilidad'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Http\Controllers\Teacher\AvailabilityController::store
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:34
 * @route '/docente/mi-disponibilidad'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/docente/mi-disponibilidad',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\AvailabilityController::store
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:34
 * @route '/docente/mi-disponibilidad'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AvailabilityController::store
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:34
 * @route '/docente/mi-disponibilidad'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Teacher\AvailabilityController::store
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:34
 * @route '/docente/mi-disponibilidad'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\AvailabilityController::store
 * @see app/Http/Controllers/Teacher/AvailabilityController.php:34
 * @route '/docente/mi-disponibilidad'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
const availability = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
}

export default availability