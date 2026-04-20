import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import schedule from './schedule'
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkCreate
 * @see app/Http/Controllers/Admin/CourseSectionController.php:243
 * @route '/admin/secciones-cursos/masivo'
 */
export const bulkCreate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkCreate.url(options),
    method: 'get',
})

bulkCreate.definition = {
    methods: ["get","head"],
    url: '/admin/secciones-cursos/masivo',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkCreate
 * @see app/Http/Controllers/Admin/CourseSectionController.php:243
 * @route '/admin/secciones-cursos/masivo'
 */
bulkCreate.url = (options?: RouteQueryOptions) => {
    return bulkCreate.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkCreate
 * @see app/Http/Controllers/Admin/CourseSectionController.php:243
 * @route '/admin/secciones-cursos/masivo'
 */
bulkCreate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: bulkCreate.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkCreate
 * @see app/Http/Controllers/Admin/CourseSectionController.php:243
 * @route '/admin/secciones-cursos/masivo'
 */
bulkCreate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: bulkCreate.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkCreate
 * @see app/Http/Controllers/Admin/CourseSectionController.php:243
 * @route '/admin/secciones-cursos/masivo'
 */
    const bulkCreateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: bulkCreate.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkCreate
 * @see app/Http/Controllers/Admin/CourseSectionController.php:243
 * @route '/admin/secciones-cursos/masivo'
 */
        bulkCreateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkCreate.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkCreate
 * @see app/Http/Controllers/Admin/CourseSectionController.php:243
 * @route '/admin/secciones-cursos/masivo'
 */
        bulkCreateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: bulkCreate.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    bulkCreate.form = bulkCreateForm
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkStore
 * @see app/Http/Controllers/Admin/CourseSectionController.php:256
 * @route '/admin/secciones-cursos/masivo'
 */
export const bulkStore = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkStore.url(options),
    method: 'post',
})

bulkStore.definition = {
    methods: ["post"],
    url: '/admin/secciones-cursos/masivo',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkStore
 * @see app/Http/Controllers/Admin/CourseSectionController.php:256
 * @route '/admin/secciones-cursos/masivo'
 */
bulkStore.url = (options?: RouteQueryOptions) => {
    return bulkStore.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkStore
 * @see app/Http/Controllers/Admin/CourseSectionController.php:256
 * @route '/admin/secciones-cursos/masivo'
 */
bulkStore.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkStore.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkStore
 * @see app/Http/Controllers/Admin/CourseSectionController.php:256
 * @route '/admin/secciones-cursos/masivo'
 */
    const bulkStoreForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkStore.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::bulkStore
 * @see app/Http/Controllers/Admin/CourseSectionController.php:256
 * @route '/admin/secciones-cursos/masivo'
 */
        bulkStoreForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkStore.url(options),
            method: 'post',
        })
    
    bulkStore.form = bulkStoreForm
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::teacherAssignment
 * @see app/Http/Controllers/Admin/CourseSectionController.php:281
 * @route '/admin/secciones-cursos/asignacion-docentes'
 */
export const teacherAssignment = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: teacherAssignment.url(options),
    method: 'get',
})

teacherAssignment.definition = {
    methods: ["get","head"],
    url: '/admin/secciones-cursos/asignacion-docentes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::teacherAssignment
 * @see app/Http/Controllers/Admin/CourseSectionController.php:281
 * @route '/admin/secciones-cursos/asignacion-docentes'
 */
teacherAssignment.url = (options?: RouteQueryOptions) => {
    return teacherAssignment.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::teacherAssignment
 * @see app/Http/Controllers/Admin/CourseSectionController.php:281
 * @route '/admin/secciones-cursos/asignacion-docentes'
 */
teacherAssignment.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: teacherAssignment.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::teacherAssignment
 * @see app/Http/Controllers/Admin/CourseSectionController.php:281
 * @route '/admin/secciones-cursos/asignacion-docentes'
 */
teacherAssignment.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: teacherAssignment.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::teacherAssignment
 * @see app/Http/Controllers/Admin/CourseSectionController.php:281
 * @route '/admin/secciones-cursos/asignacion-docentes'
 */
    const teacherAssignmentForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: teacherAssignment.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::teacherAssignment
 * @see app/Http/Controllers/Admin/CourseSectionController.php:281
 * @route '/admin/secciones-cursos/asignacion-docentes'
 */
        teacherAssignmentForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: teacherAssignment.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::teacherAssignment
 * @see app/Http/Controllers/Admin/CourseSectionController.php:281
 * @route '/admin/secciones-cursos/asignacion-docentes'
 */
        teacherAssignmentForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: teacherAssignment.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    teacherAssignment.form = teacherAssignmentForm
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::updateBulk
 * @see app/Http/Controllers/Admin/CourseSectionController.php:313
 * @route '/admin/secciones-cursos/update-bulk'
 */
export const updateBulk = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateBulk.url(options),
    method: 'post',
})

updateBulk.definition = {
    methods: ["post"],
    url: '/admin/secciones-cursos/update-bulk',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::updateBulk
 * @see app/Http/Controllers/Admin/CourseSectionController.php:313
 * @route '/admin/secciones-cursos/update-bulk'
 */
updateBulk.url = (options?: RouteQueryOptions) => {
    return updateBulk.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::updateBulk
 * @see app/Http/Controllers/Admin/CourseSectionController.php:313
 * @route '/admin/secciones-cursos/update-bulk'
 */
updateBulk.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateBulk.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::updateBulk
 * @see app/Http/Controllers/Admin/CourseSectionController.php:313
 * @route '/admin/secciones-cursos/update-bulk'
 */
    const updateBulkForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateBulk.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::updateBulk
 * @see app/Http/Controllers/Admin/CourseSectionController.php:313
 * @route '/admin/secciones-cursos/update-bulk'
 */
        updateBulkForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateBulk.url(options),
            method: 'post',
        })
    
    updateBulk.form = updateBulkForm
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::index
 * @see app/Http/Controllers/Admin/CourseSectionController.php:24
 * @route '/admin/secciones-cursos'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/secciones-cursos',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::index
 * @see app/Http/Controllers/Admin/CourseSectionController.php:24
 * @route '/admin/secciones-cursos'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::index
 * @see app/Http/Controllers/Admin/CourseSectionController.php:24
 * @route '/admin/secciones-cursos'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::index
 * @see app/Http/Controllers/Admin/CourseSectionController.php:24
 * @route '/admin/secciones-cursos'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::index
 * @see app/Http/Controllers/Admin/CourseSectionController.php:24
 * @route '/admin/secciones-cursos'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::index
 * @see app/Http/Controllers/Admin/CourseSectionController.php:24
 * @route '/admin/secciones-cursos'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::index
 * @see app/Http/Controllers/Admin/CourseSectionController.php:24
 * @route '/admin/secciones-cursos'
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
* @see \App\Http\Controllers\Admin\CourseSectionController::create
 * @see app/Http/Controllers/Admin/CourseSectionController.php:48
 * @route '/admin/secciones-cursos/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/secciones-cursos/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::create
 * @see app/Http/Controllers/Admin/CourseSectionController.php:48
 * @route '/admin/secciones-cursos/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::create
 * @see app/Http/Controllers/Admin/CourseSectionController.php:48
 * @route '/admin/secciones-cursos/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::create
 * @see app/Http/Controllers/Admin/CourseSectionController.php:48
 * @route '/admin/secciones-cursos/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::create
 * @see app/Http/Controllers/Admin/CourseSectionController.php:48
 * @route '/admin/secciones-cursos/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::create
 * @see app/Http/Controllers/Admin/CourseSectionController.php:48
 * @route '/admin/secciones-cursos/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::create
 * @see app/Http/Controllers/Admin/CourseSectionController.php:48
 * @route '/admin/secciones-cursos/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::store
 * @see app/Http/Controllers/Admin/CourseSectionController.php:103
 * @route '/admin/secciones-cursos'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/secciones-cursos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::store
 * @see app/Http/Controllers/Admin/CourseSectionController.php:103
 * @route '/admin/secciones-cursos'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::store
 * @see app/Http/Controllers/Admin/CourseSectionController.php:103
 * @route '/admin/secciones-cursos'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::store
 * @see app/Http/Controllers/Admin/CourseSectionController.php:103
 * @route '/admin/secciones-cursos'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::store
 * @see app/Http/Controllers/Admin/CourseSectionController.php:103
 * @route '/admin/secciones-cursos'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::show
 * @see app/Http/Controllers/Admin/CourseSectionController.php:0
 * @route '/admin/secciones-cursos/{courseSection}'
 */
export const show = (args: { courseSection: string | number } | [courseSection: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/secciones-cursos/{courseSection}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::show
 * @see app/Http/Controllers/Admin/CourseSectionController.php:0
 * @route '/admin/secciones-cursos/{courseSection}'
 */
show.url = (args: { courseSection: string | number } | [courseSection: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { courseSection: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    courseSection: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        courseSection: args.courseSection,
                }

    return show.definition.url
            .replace('{courseSection}', parsedArgs.courseSection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::show
 * @see app/Http/Controllers/Admin/CourseSectionController.php:0
 * @route '/admin/secciones-cursos/{courseSection}'
 */
show.get = (args: { courseSection: string | number } | [courseSection: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::show
 * @see app/Http/Controllers/Admin/CourseSectionController.php:0
 * @route '/admin/secciones-cursos/{courseSection}'
 */
show.head = (args: { courseSection: string | number } | [courseSection: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::show
 * @see app/Http/Controllers/Admin/CourseSectionController.php:0
 * @route '/admin/secciones-cursos/{courseSection}'
 */
    const showForm = (args: { courseSection: string | number } | [courseSection: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::show
 * @see app/Http/Controllers/Admin/CourseSectionController.php:0
 * @route '/admin/secciones-cursos/{courseSection}'
 */
        showForm.get = (args: { courseSection: string | number } | [courseSection: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::show
 * @see app/Http/Controllers/Admin/CourseSectionController.php:0
 * @route '/admin/secciones-cursos/{courseSection}'
 */
        showForm.head = (args: { courseSection: string | number } | [courseSection: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\CourseSectionController::edit
 * @see app/Http/Controllers/Admin/CourseSectionController.php:133
 * @route '/admin/secciones-cursos/{courseSection}/edit'
 */
export const edit = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/secciones-cursos/{courseSection}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::edit
 * @see app/Http/Controllers/Admin/CourseSectionController.php:133
 * @route '/admin/secciones-cursos/{courseSection}/edit'
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
* @see \App\Http\Controllers\Admin\CourseSectionController::edit
 * @see app/Http/Controllers/Admin/CourseSectionController.php:133
 * @route '/admin/secciones-cursos/{courseSection}/edit'
 */
edit.get = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::edit
 * @see app/Http/Controllers/Admin/CourseSectionController.php:133
 * @route '/admin/secciones-cursos/{courseSection}/edit'
 */
edit.head = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::edit
 * @see app/Http/Controllers/Admin/CourseSectionController.php:133
 * @route '/admin/secciones-cursos/{courseSection}/edit'
 */
    const editForm = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::edit
 * @see app/Http/Controllers/Admin/CourseSectionController.php:133
 * @route '/admin/secciones-cursos/{courseSection}/edit'
 */
        editForm.get = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::edit
 * @see app/Http/Controllers/Admin/CourseSectionController.php:133
 * @route '/admin/secciones-cursos/{courseSection}/edit'
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
* @see \App\Http\Controllers\Admin\CourseSectionController::update
 * @see app/Http/Controllers/Admin/CourseSectionController.php:188
 * @route '/admin/secciones-cursos/{courseSection}'
 */
export const update = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/secciones-cursos/{courseSection}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::update
 * @see app/Http/Controllers/Admin/CourseSectionController.php:188
 * @route '/admin/secciones-cursos/{courseSection}'
 */
update.url = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{courseSection}', parsedArgs.courseSection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::update
 * @see app/Http/Controllers/Admin/CourseSectionController.php:188
 * @route '/admin/secciones-cursos/{courseSection}'
 */
update.put = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::update
 * @see app/Http/Controllers/Admin/CourseSectionController.php:188
 * @route '/admin/secciones-cursos/{courseSection}'
 */
update.patch = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::update
 * @see app/Http/Controllers/Admin/CourseSectionController.php:188
 * @route '/admin/secciones-cursos/{courseSection}'
 */
    const updateForm = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::update
 * @see app/Http/Controllers/Admin/CourseSectionController.php:188
 * @route '/admin/secciones-cursos/{courseSection}'
 */
        updateForm.put = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::update
 * @see app/Http/Controllers/Admin/CourseSectionController.php:188
 * @route '/admin/secciones-cursos/{courseSection}'
 */
        updateForm.patch = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Http\Controllers\Admin\CourseSectionController::destroy
 * @see app/Http/Controllers/Admin/CourseSectionController.php:221
 * @route '/admin/secciones-cursos/{courseSection}'
 */
export const destroy = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/secciones-cursos/{courseSection}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::destroy
 * @see app/Http/Controllers/Admin/CourseSectionController.php:221
 * @route '/admin/secciones-cursos/{courseSection}'
 */
destroy.url = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{courseSection}', parsedArgs.courseSection.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\CourseSectionController::destroy
 * @see app/Http/Controllers/Admin/CourseSectionController.php:221
 * @route '/admin/secciones-cursos/{courseSection}'
 */
destroy.delete = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\CourseSectionController::destroy
 * @see app/Http/Controllers/Admin/CourseSectionController.php:221
 * @route '/admin/secciones-cursos/{courseSection}'
 */
    const destroyForm = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\CourseSectionController::destroy
 * @see app/Http/Controllers/Admin/CourseSectionController.php:221
 * @route '/admin/secciones-cursos/{courseSection}'
 */
        destroyForm.delete = (args: { courseSection: number | { id: number } } | [courseSection: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const course_sections = {
    bulkCreate: Object.assign(bulkCreate, bulkCreate),
bulkStore: Object.assign(bulkStore, bulkStore),
teacherAssignment: Object.assign(teacherAssignment, teacherAssignment),
updateBulk: Object.assign(updateBulk, updateBulk),
index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
schedule: Object.assign(schedule, schedule),
}

export default course_sections