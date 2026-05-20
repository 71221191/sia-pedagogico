import sections from './sections'
import grades from './grades'
import domains from './domains'
import competencies from './competencies'
import practice from './practice'
import attendance from './attendance'
import portfolio from './portfolio'
import thesisReview from './thesis-review'
import availability from './availability'
import units from './units'
import resources from './resources'
import tasks from './tasks'
import submissions from './submissions'
import forums from './forums'
const teacher = {
    sections: Object.assign(sections, sections),
grades: Object.assign(grades, grades),
domains: Object.assign(domains, domains),
competencies: Object.assign(competencies, competencies),
practice: Object.assign(practice, practice),
attendance: Object.assign(attendance, attendance),
portfolio: Object.assign(portfolio, portfolio),
thesisReview: Object.assign(thesisReview, thesisReview),
availability: Object.assign(availability, availability),
units: Object.assign(units, units),
resources: Object.assign(resources, resources),
tasks: Object.assign(tasks, tasks),
submissions: Object.assign(submissions, submissions),
forums: Object.assign(forums, forums),
}

export default teacher