import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Student\ThesisController::index
 * @see app/Http/Controllers/Student/ThesisController.php:15
 * @route '/estudiante/tesis'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/estudiante/tesis',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\ThesisController::index
 * @see app/Http/Controllers/Student/ThesisController.php:15
 * @route '/estudiante/tesis'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ThesisController::index
 * @see app/Http/Controllers/Student/ThesisController.php:15
 * @route '/estudiante/tesis'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\ThesisController::index
 * @see app/Http/Controllers/Student/ThesisController.php:15
 * @route '/estudiante/tesis'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\ThesisController::index
 * @see app/Http/Controllers/Student/ThesisController.php:15
 * @route '/estudiante/tesis'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\ThesisController::index
 * @see app/Http/Controllers/Student/ThesisController.php:15
 * @route '/estudiante/tesis'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\ThesisController::index
 * @see app/Http/Controllers/Student/ThesisController.php:15
 * @route '/estudiante/tesis'
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
* @see \App\Http\Controllers\Student\ThesisController::create
 * @see app/Http/Controllers/Student/ThesisController.php:35
 * @route '/estudiante/tesis/registrar'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/estudiante/tesis/registrar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Student\ThesisController::create
 * @see app/Http/Controllers/Student/ThesisController.php:35
 * @route '/estudiante/tesis/registrar'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ThesisController::create
 * @see app/Http/Controllers/Student/ThesisController.php:35
 * @route '/estudiante/tesis/registrar'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Student\ThesisController::create
 * @see app/Http/Controllers/Student/ThesisController.php:35
 * @route '/estudiante/tesis/registrar'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Student\ThesisController::create
 * @see app/Http/Controllers/Student/ThesisController.php:35
 * @route '/estudiante/tesis/registrar'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Student\ThesisController::create
 * @see app/Http/Controllers/Student/ThesisController.php:35
 * @route '/estudiante/tesis/registrar'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Student\ThesisController::create
 * @see app/Http/Controllers/Student/ThesisController.php:35
 * @route '/estudiante/tesis/registrar'
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
* @see \App\Http\Controllers\Student\ThesisController::store
 * @see app/Http/Controllers/Student/ThesisController.php:48
 * @route '/estudiante/tesis/registrar'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/estudiante/tesis/registrar',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Student\ThesisController::store
 * @see app/Http/Controllers/Student/ThesisController.php:48
 * @route '/estudiante/tesis/registrar'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ThesisController::store
 * @see app/Http/Controllers/Student/ThesisController.php:48
 * @route '/estudiante/tesis/registrar'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Student\ThesisController::store
 * @see app/Http/Controllers/Student/ThesisController.php:48
 * @route '/estudiante/tesis/registrar'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Student\ThesisController::store
 * @see app/Http/Controllers/Student/ThesisController.php:48
 * @route '/estudiante/tesis/registrar'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Http\Controllers\Student\ThesisController::uploadDocument
 * @see app/Http/Controllers/Student/ThesisController.php:79
 * @route '/estudiante/tesis/{project}/documento'
 */
export const uploadDocument = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadDocument.url(args, options),
    method: 'post',
})

uploadDocument.definition = {
    methods: ["post"],
    url: '/estudiante/tesis/{project}/documento',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\Student\ThesisController::uploadDocument
 * @see app/Http/Controllers/Student/ThesisController.php:79
 * @route '/estudiante/tesis/{project}/documento'
 */
uploadDocument.url = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { project: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { project: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    project: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        project: typeof args.project === 'object'
                ? args.project.id
                : args.project,
                }

    return uploadDocument.definition.url
            .replace('{project}', parsedArgs.project.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Student\ThesisController::uploadDocument
 * @see app/Http/Controllers/Student/ThesisController.php:79
 * @route '/estudiante/tesis/{project}/documento'
 */
uploadDocument.post = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: uploadDocument.url(args, options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\Student\ThesisController::uploadDocument
 * @see app/Http/Controllers/Student/ThesisController.php:79
 * @route '/estudiante/tesis/{project}/documento'
 */
    const uploadDocumentForm = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: uploadDocument.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Student\ThesisController::uploadDocument
 * @see app/Http/Controllers/Student/ThesisController.php:79
 * @route '/estudiante/tesis/{project}/documento'
 */
        uploadDocumentForm.post = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: uploadDocument.url(args, options),
            method: 'post',
        })
    
    uploadDocument.form = uploadDocumentForm
const thesis = {
    index: Object.assign(index, index),
create: Object.assign(create, create),
store: Object.assign(store, store),
uploadDocument: Object.assign(uploadDocument, uploadDocument),
}

export default thesis