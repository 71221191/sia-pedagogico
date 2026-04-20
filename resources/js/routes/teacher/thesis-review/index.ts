import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::index
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:15
 * @route '/docente/revision-tesis'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/docente/revision-tesis',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::index
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:15
 * @route '/docente/revision-tesis'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::index
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:15
 * @route '/docente/revision-tesis'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::index
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:15
 * @route '/docente/revision-tesis'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::index
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:15
 * @route '/docente/revision-tesis'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::index
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:15
 * @route '/docente/revision-tesis'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::index
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:15
 * @route '/docente/revision-tesis'
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
* @see \App\Http\Controllers\Teacher\ThesisReviewController::updateScore
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:31
 * @route '/docente/revision-tesis/{project}/calificar'
 */
export const updateScore = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateScore.url(args, options),
    method: 'patch',
})

updateScore.definition = {
    methods: ["patch"],
    url: '/docente/revision-tesis/{project}/calificar',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::updateScore
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:31
 * @route '/docente/revision-tesis/{project}/calificar'
 */
updateScore.url = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
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

    return updateScore.definition.url
            .replace('{project}', parsedArgs.project.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::updateScore
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:31
 * @route '/docente/revision-tesis/{project}/calificar'
 */
updateScore.patch = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updateScore.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::updateScore
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:31
 * @route '/docente/revision-tesis/{project}/calificar'
 */
    const updateScoreForm = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateScore.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\Teacher\ThesisReviewController::updateScore
 * @see app/Http/Controllers/Teacher/ThesisReviewController.php:31
 * @route '/docente/revision-tesis/{project}/calificar'
 */
        updateScoreForm.patch = (args: { project: number | { id: number } } | [project: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateScore.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateScore.form = updateScoreForm
const thesisReview = {
    index: Object.assign(index, index),
updateScore: Object.assign(updateScore, updateScore),
}

export default thesisReview