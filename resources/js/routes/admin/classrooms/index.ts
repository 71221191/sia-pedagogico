import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ClassroomController::index
 * @see app/Http/Controllers/Admin/ClassroomController.php:12
 * @route '/admin/ambientes'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/ambientes',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ClassroomController::index
 * @see app/Http/Controllers/Admin/ClassroomController.php:12
 * @route '/admin/ambientes'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ClassroomController::index
 * @see app/Http/Controllers/Admin/ClassroomController.php:12
 * @route '/admin/ambientes'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ClassroomController::index
 * @see app/Http/Controllers/Admin/ClassroomController.php:12
 * @route '/admin/ambientes'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ClassroomController::index
 * @see app/Http/Controllers/Admin/ClassroomController.php:12
 * @route '/admin/ambientes'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ClassroomController::index
 * @see app/Http/Controllers/Admin/ClassroomController.php:12
 * @route '/admin/ambientes'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ClassroomController::index
 * @see app/Http/Controllers/Admin/ClassroomController.php:12
 * @route '/admin/ambientes'
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
* @see \App\Http\Controllers\Admin\ClassroomController::create
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/admin/ambientes/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ClassroomController::create
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ClassroomController::create
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ClassroomController::create
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ClassroomController::create
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ClassroomController::create
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ClassroomController::create
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/create'
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
* @see \App\Http\Controllers\Admin\ClassroomController::store
 * @see app/Http/Controllers/Admin/ClassroomController.php:22
 * @route '/admin/ambientes'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/ambientes',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ClassroomController::store
 * @see app/Http/Controllers/Admin/ClassroomController.php:22
 * @route '/admin/ambientes'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ClassroomController::store
 * @see app/Http/Controllers/Admin/ClassroomController.php:22
 * @route '/admin/ambientes'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ClassroomController::store
 * @see app/Http/Controllers/Admin/ClassroomController.php:22
 * @route '/admin/ambientes'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ClassroomController::store
 * @see app/Http/Controllers/Admin/ClassroomController.php:22
 * @route '/admin/ambientes'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Admin\ClassroomController::show
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}'
 */
export const show = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/admin/ambientes/{ambiente}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ClassroomController::show
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}'
 */
show.url = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ambiente: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    ambiente: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        ambiente: args.ambiente,
                }

    return show.definition.url
            .replace('{ambiente}', parsedArgs.ambiente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ClassroomController::show
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}'
 */
show.get = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ClassroomController::show
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}'
 */
show.head = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ClassroomController::show
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}'
 */
    const showForm = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ClassroomController::show
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}'
 */
        showForm.get = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ClassroomController::show
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}'
 */
        showForm.head = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\ClassroomController::edit
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}/edit'
 */
export const edit = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/admin/ambientes/{ambiente}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ClassroomController::edit
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}/edit'
 */
edit.url = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ambiente: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    ambiente: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        ambiente: args.ambiente,
                }

    return edit.definition.url
            .replace('{ambiente}', parsedArgs.ambiente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ClassroomController::edit
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}/edit'
 */
edit.get = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ClassroomController::edit
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}/edit'
 */
edit.head = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ClassroomController::edit
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}/edit'
 */
    const editForm = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ClassroomController::edit
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}/edit'
 */
        editForm.get = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ClassroomController::edit
 * @see app/Http/Controllers/Admin/ClassroomController.php:0
 * @route '/admin/ambientes/{ambiente}/edit'
 */
        editForm.head = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Admin\ClassroomController::update
 * @see app/Http/Controllers/Admin/ClassroomController.php:35
 * @route '/admin/ambientes/{ambiente}'
 */
export const update = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/ambientes/{ambiente}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Http\Controllers\Admin\ClassroomController::update
 * @see app/Http/Controllers/Admin/ClassroomController.php:35
 * @route '/admin/ambientes/{ambiente}'
 */
update.url = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ambiente: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    ambiente: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        ambiente: args.ambiente,
                }

    return update.definition.url
            .replace('{ambiente}', parsedArgs.ambiente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ClassroomController::update
 * @see app/Http/Controllers/Admin/ClassroomController.php:35
 * @route '/admin/ambientes/{ambiente}'
 */
update.put = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Http\Controllers\Admin\ClassroomController::update
 * @see app/Http/Controllers/Admin/ClassroomController.php:35
 * @route '/admin/ambientes/{ambiente}'
 */
update.patch = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Admin\ClassroomController::update
 * @see app/Http/Controllers/Admin/ClassroomController.php:35
 * @route '/admin/ambientes/{ambiente}'
 */
    const updateForm = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ClassroomController::update
 * @see app/Http/Controllers/Admin/ClassroomController.php:35
 * @route '/admin/ambientes/{ambiente}'
 */
        updateForm.put = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Http\Controllers\Admin\ClassroomController::update
 * @see app/Http/Controllers/Admin/ClassroomController.php:35
 * @route '/admin/ambientes/{ambiente}'
 */
        updateForm.patch = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Http\Controllers\Admin\ClassroomController::destroy
 * @see app/Http/Controllers/Admin/ClassroomController.php:48
 * @route '/admin/ambientes/{ambiente}'
 */
export const destroy = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/ambientes/{ambiente}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Http\Controllers\Admin\ClassroomController::destroy
 * @see app/Http/Controllers/Admin/ClassroomController.php:48
 * @route '/admin/ambientes/{ambiente}'
 */
destroy.url = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { ambiente: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    ambiente: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        ambiente: args.ambiente,
                }

    return destroy.definition.url
            .replace('{ambiente}', parsedArgs.ambiente.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ClassroomController::destroy
 * @see app/Http/Controllers/Admin/ClassroomController.php:48
 * @route '/admin/ambientes/{ambiente}'
 */
destroy.delete = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Http\Controllers\Admin\ClassroomController::destroy
 * @see app/Http/Controllers/Admin/ClassroomController.php:48
 * @route '/admin/ambientes/{ambiente}'
 */
    const destroyForm = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ClassroomController::destroy
 * @see app/Http/Controllers/Admin/ClassroomController.php:48
 * @route '/admin/ambientes/{ambiente}'
 */
        destroyForm.delete = (args: { ambiente: string | number } | [ambiente: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const classrooms = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
show: Object.assign(show, show),
edit: Object.assign(edit, edit),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default classrooms