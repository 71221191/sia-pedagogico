import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::index
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:16
 * @route '/docente/tareas/{task}/entregas'
 */
export const index = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/docente/tareas/{task}/entregas',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::index
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:16
 * @route '/docente/tareas/{task}/entregas'
 */
index.url = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { task: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { task: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    task: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        task: typeof args.task === 'object'
                ? args.task.id
                : args.task,
                }

    return index.definition.url
            .replace('{task}', parsedArgs.task.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::index
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:16
 * @route '/docente/tareas/{task}/entregas'
 */
index.get = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(args, options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::index
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:16
 * @route '/docente/tareas/{task}/entregas'
 */
index.head = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(args, options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::index
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:16
 * @route '/docente/tareas/{task}/entregas'
 */
    const indexForm = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::index
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:16
 * @route '/docente/tareas/{task}/entregas'
 */
        indexForm.get = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::index
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:16
 * @route '/docente/tareas/{task}/entregas'
 */
        indexForm.head = (args: { task: number | { id: number } } | [task: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
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
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::grade
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:35
 * @route '/docente/entregas/{submission}/calificar'
 */
export const grade = (args: { submission: number | { id: number } } | [submission: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: grade.url(args, options),
    method: 'patch',
})

grade.definition = {
    methods: ["patch"],
    url: '/docente/entregas/{submission}/calificar',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::grade
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:35
 * @route '/docente/entregas/{submission}/calificar'
 */
grade.url = (args: { submission: number | { id: number } } | [submission: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { submission: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { submission: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    submission: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        submission: typeof args.submission === 'object'
                ? args.submission.id
                : args.submission,
                }

    return grade.definition.url
            .replace('{submission}', parsedArgs.submission.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::grade
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:35
 * @route '/docente/entregas/{submission}/calificar'
 */
grade.patch = (args: { submission: number | { id: number } } | [submission: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: grade.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::grade
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:35
 * @route '/docente/entregas/{submission}/calificar'
 */
    const gradeForm = (args: { submission: number | { id: number } } | [submission: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: grade.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\TaskSubmissionController::grade
 * @see app/Http/Controllers/Teacher/TaskSubmissionController.php:35
 * @route '/docente/entregas/{submission}/calificar'
 */
        gradeForm.patch = (args: { submission: number | { id: number } } | [submission: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: grade.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    grade.form = gradeForm
const submissions = {
    index: Object.assign(index, index),
grade: Object.assign(grade, grade),
}

export default submissions