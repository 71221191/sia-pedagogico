import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ScheduleController::edit
 * @see app/Http/Controllers/Admin/ScheduleController.php:24
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
export const edit = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/secciones-cursos/{courseSection}/horario',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::edit
 * @see app/Http/Controllers/Admin/ScheduleController.php:24
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
edit.url = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { courseSection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { courseSection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    courseSection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        courseSection: typeof args.courseSection === 'object'
                ? args.courseSection.id
                : args.courseSection,
                }

    return edit.definition.url
            .replace('{courseSection}', parsedArgs.courseSection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::edit
 * @see app/Http/Controllers/Admin/ScheduleController.php:24
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
edit.get = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ScheduleController::edit
 * @see app/Http/Controllers/Admin/ScheduleController.php:24
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
edit.head = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ScheduleController::edit
 * @see app/Http/Controllers/Admin/ScheduleController.php:24
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
    const editForm = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ScheduleController::edit
 * @see app/Http/Controllers/Admin/ScheduleController.php:24
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
        editForm.get = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ScheduleController::edit
 * @see app/Http/Controllers/Admin/ScheduleController.php:24
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
        editForm.head = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Http\Controllers\Admin\ScheduleController::store
 * @see app/Http/Controllers/Admin/ScheduleController.php:44
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
export const store = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/secciones-cursos/{courseSection}/horario',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::store
 * @see app/Http/Controllers/Admin/ScheduleController.php:44
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
store.url = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { courseSection: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { courseSection: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    courseSection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        courseSection: typeof args.courseSection === 'object'
                ? args.courseSection.id
                : args.courseSection,
                }

    return store.definition.url
            .replace('{courseSection}', parsedArgs.courseSection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::store
 * @see app/Http/Controllers/Admin/ScheduleController.php:44
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
store.post = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ScheduleController::store
 * @see app/Http/Controllers/Admin/ScheduleController.php:44
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
    const storeForm = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ScheduleController::store
 * @see app/Http/Controllers/Admin/ScheduleController.php:44
 * @route '/admin/secciones-cursos/{courseSection}/horario'
 */
        storeForm.post = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
 * @see app/Http/Controllers/Admin/ScheduleController.php:79
 * @route '/admin/horarios/{schedule}'
 */
export const destroy = (args: { schedule: number | { id: number } } | [schedule: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/horarios/{schedule}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
 * @see app/Http/Controllers/Admin/ScheduleController.php:79
 * @route '/admin/horarios/{schedule}'
 */
destroy.url = (args: { schedule: number | { id: number } } | [schedule: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { schedule: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { schedule: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    schedule: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        schedule: typeof args.schedule === 'object'
                ? args.schedule.id
                : args.schedule,
                }

    return destroy.definition.url
            .replace('{schedule}', parsedArgs.schedule.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
 * @see app/Http/Controllers/Admin/ScheduleController.php:79
 * @route '/admin/horarios/{schedule}'
 */
destroy.delete = (args: { schedule: number | { id: number } } | [schedule: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
 * @see app/Http/Controllers/Admin/ScheduleController.php:79
 * @route '/admin/horarios/{schedule}'
 */
    const destroyForm = (args: { schedule: number | { id: number } } | [schedule: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ScheduleController::destroy
 * @see app/Http/Controllers/Admin/ScheduleController.php:79
 * @route '/admin/horarios/{schedule}'
 */
        destroyForm.delete = (args: { schedule: number | { id: number } } | [schedule: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const schedule = {
    edit: Object.assign(edit, edit),
store: Object.assign(store, store),
destroy: Object.assign(destroy, destroy),
}

export default schedule