import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::history
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:13
 * @route '/admin/importaciones/historial'
 */
export const history = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})

history.definition = {
    methods: ["get","head"],
    url: '/admin/importaciones/historial',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::history
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:13
 * @route '/admin/importaciones/historial'
 */
history.url = (options?: RouteQueryOptions) => {
    return history.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::history
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:13
 * @route '/admin/importaciones/historial'
 */
history.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::history
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:13
 * @route '/admin/importaciones/historial'
 */
history.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: history.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ImportHistoryController::history
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:13
 * @route '/admin/importaciones/historial'
 */
    const historyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: history.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ImportHistoryController::history
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:13
 * @route '/admin/importaciones/historial'
 */
        historyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: history.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ImportHistoryController::history
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:13
 * @route '/admin/importaciones/historial'
 */
        historyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: history.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    history.form = historyForm
/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::download
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:24
 * @route '/admin/importaciones/descargar/{import}'
 */
export const download = (args: { import: number | { id: number } } | [importParam: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/admin/importaciones/descargar/{import}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::download
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:24
 * @route '/admin/importaciones/descargar/{import}'
 */
download.url = (args: { import: number | { id: number } } | [importParam: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { import: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { import: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    import: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        import: typeof args.import === 'object'
                ? args.import.id
                : args.import,
                }

    return download.definition.url
            .replace('{import}', parsedArgs.import.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::download
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:24
 * @route '/admin/importaciones/descargar/{import}'
 */
download.get = (args: { import: number | { id: number } } | [importParam: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Admin\ImportHistoryController::download
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:24
 * @route '/admin/importaciones/descargar/{import}'
 */
download.head = (args: { import: number | { id: number } } | [importParam: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Admin\ImportHistoryController::download
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:24
 * @route '/admin/importaciones/descargar/{import}'
 */
    const downloadForm = (args: { import: number | { id: number } } | [importParam: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: download.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Admin\ImportHistoryController::download
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:24
 * @route '/admin/importaciones/descargar/{import}'
 */
        downloadForm.get = (args: { import: number | { id: number } } | [importParam: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Admin\ImportHistoryController::download
 * @see app/Http/Controllers/Admin/ImportHistoryController.php:24
 * @route '/admin/importaciones/descargar/{import}'
 */
        downloadForm.head = (args: { import: number | { id: number } } | [importParam: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    download.form = downloadForm
/**
 * @see routes/web.php:339
 * @route '/admin/importaciones'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/importaciones',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web.php:339
 * @route '/admin/importaciones'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
 * @see routes/web.php:339
 * @route '/admin/importaciones'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
 * @see routes/web.php:339
 * @route '/admin/importaciones'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
 * @see routes/web.php:339
 * @route '/admin/importaciones'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
 * @see routes/web.php:339
 * @route '/admin/importaciones'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
 * @see routes/web.php:339
 * @route '/admin/importaciones'
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
* @see \App\Http\Controllers\Admin\ImportController::process
 * @see app/Http/Controllers/Admin/ImportController.php:17
 * @route '/admin/importaciones/procesar'
 */
export const process = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: process.url(options),
    method: 'post',
})

process.definition = {
    methods: ["post"],
    url: '/admin/importaciones/procesar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ImportController::process
 * @see app/Http/Controllers/Admin/ImportController.php:17
 * @route '/admin/importaciones/procesar'
 */
process.url = (options?: RouteQueryOptions) => {
    return process.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ImportController::process
 * @see app/Http/Controllers/Admin/ImportController.php:17
 * @route '/admin/importaciones/procesar'
 */
process.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: process.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ImportController::process
 * @see app/Http/Controllers/Admin/ImportController.php:17
 * @route '/admin/importaciones/procesar'
 */
    const processForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: process.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ImportController::process
 * @see app/Http/Controllers/Admin/ImportController.php:17
 * @route '/admin/importaciones/procesar'
 */
        processForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: process.url(options),
            method: 'post',
        })
    
    process.form = processForm
/**
* @see \App\Http\Controllers\Admin\ImportController::activeStudents
 * @see app/Http/Controllers/Admin/ImportController.php:82
 * @route '/admin/importaciones/alumnos-activos'
 */
export const activeStudents = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activeStudents.url(options),
    method: 'post',
})

activeStudents.definition = {
    methods: ["post"],
    url: '/admin/importaciones/alumnos-activos',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Admin\ImportController::activeStudents
 * @see app/Http/Controllers/Admin/ImportController.php:82
 * @route '/admin/importaciones/alumnos-activos'
 */
activeStudents.url = (options?: RouteQueryOptions) => {
    return activeStudents.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Admin\ImportController::activeStudents
 * @see app/Http/Controllers/Admin/ImportController.php:82
 * @route '/admin/importaciones/alumnos-activos'
 */
activeStudents.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: activeStudents.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Admin\ImportController::activeStudents
 * @see app/Http/Controllers/Admin/ImportController.php:82
 * @route '/admin/importaciones/alumnos-activos'
 */
    const activeStudentsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: activeStudents.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Admin\ImportController::activeStudents
 * @see app/Http/Controllers/Admin/ImportController.php:82
 * @route '/admin/importaciones/alumnos-activos'
 */
        activeStudentsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: activeStudents.url(options),
            method: 'post',
        })
    
    activeStudents.form = activeStudentsForm
const imports = {
    history: Object.assign(history, history),
download: Object.assign(download, download),
index: Object.assign(index, index),
process: Object.assign(process, process),
activeStudents: Object.assign(activeStudents, activeStudents),
}

export default imports