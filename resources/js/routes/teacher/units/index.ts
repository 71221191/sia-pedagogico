import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::index
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:17
 * @route '/docente/secciones/{section}/unidades'
 */
export const index = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/docente/secciones/{section}/unidades',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::index
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:17
 * @route '/docente/secciones/{section}/unidades'
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
* @see \App\Http\Controllers\Teacher\AcademicUnitController::index
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:17
 * @route '/docente/secciones/{section}/unidades'
 */
index.get = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::index
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:17
 * @route '/docente/secciones/{section}/unidades'
 */
index.head = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::index
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:17
 * @route '/docente/secciones/{section}/unidades'
 */
    const indexForm = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::index
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:17
 * @route '/docente/secciones/{section}/unidades'
 */
        indexForm.get = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::index
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:17
 * @route '/docente/secciones/{section}/unidades'
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
* @see \App\Http\Controllers\Teacher\AcademicUnitController::storeBatch
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:33
 * @route '/docente/secciones/{section}/unidades-batch'
 */
export const storeBatch = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeBatch.url(args, options),
    method: 'post',
})

storeBatch.definition = {
    methods: ["post"],
    url: '/docente/secciones/{section}/unidades-batch',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::storeBatch
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:33
 * @route '/docente/secciones/{section}/unidades-batch'
 */
storeBatch.url = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return storeBatch.definition.url
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::storeBatch
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:33
 * @route '/docente/secciones/{section}/unidades-batch'
 */
storeBatch.post = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeBatch.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::storeBatch
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:33
 * @route '/docente/secciones/{section}/unidades-batch'
 */
    const storeBatchForm = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: storeBatch.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::storeBatch
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:33
 * @route '/docente/secciones/{section}/unidades-batch'
 */
        storeBatchForm.post = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: storeBatch.url(args, options),
            method: 'post',
        })
    
    storeBatch.form = storeBatchForm
/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::update
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:93
 * @route '/docente/unidades/{unit}'
 */
export const update = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/docente/unidades/{unit}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::update
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:93
 * @route '/docente/unidades/{unit}'
 */
update.url = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{unit}', parsedArgs.unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::update
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:93
 * @route '/docente/unidades/{unit}'
 */
update.put = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::update
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:93
 * @route '/docente/unidades/{unit}'
 */
    const updateForm = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::update
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:93
 * @route '/docente/unidades/{unit}'
 */
        updateForm.put = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::destroy
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:101
 * @route '/docente/unidades/{unit}'
 */
export const destroy = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/docente/unidades/{unit}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::destroy
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:101
 * @route '/docente/unidades/{unit}'
 */
destroy.url = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{unit}', parsedArgs.unit.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::destroy
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:101
 * @route '/docente/unidades/{unit}'
 */
destroy.delete = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::destroy
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:101
 * @route '/docente/unidades/{unit}'
 */
    const destroyForm = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::destroy
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:101
 * @route '/docente/unidades/{unit}'
 */
        destroyForm.delete = (args: { unit: number | { id: number } } | [unit: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::addOne
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:61
 * @route '/docente/secciones/{section}/unidades/agregar-una'
 */
export const addOne = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addOne.url(args, options),
    method: 'post',
})

addOne.definition = {
    methods: ["post"],
    url: '/docente/secciones/{section}/unidades/agregar-una',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::addOne
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:61
 * @route '/docente/secciones/{section}/unidades/agregar-una'
 */
addOne.url = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return addOne.definition.url
            .replace('{section}', parsedArgs.section.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::addOne
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:61
 * @route '/docente/secciones/{section}/unidades/agregar-una'
 */
addOne.post = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: addOne.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::addOne
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:61
 * @route '/docente/secciones/{section}/unidades/agregar-una'
 */
    const addOneForm = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: addOne.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\AcademicUnitController::addOne
 * @see app/Http/Controllers/Teacher/AcademicUnitController.php:61
 * @route '/docente/secciones/{section}/unidades/agregar-una'
 */
        addOneForm.post = (args: { section: number | { id: number } } | [section: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: addOne.url(args, options),
            method: 'post',
        })
    
    addOne.form = addOneForm
const units = {
    index: Object.assign(index, index),
storeBatch: Object.assign(storeBatch, storeBatch),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
addOne: Object.assign(addOne, addOne),
}

export default units