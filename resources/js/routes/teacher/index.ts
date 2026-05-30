import sections from './sections'
import grades from './grades'
import domains from './domains'
import competencies from './competencies'
import practice from './practice'
import syllabus from './syllabus'
import thesisReview from './thesis-review'
import availability from './availability'
const teacher = {
    sections: Object.assign(sections, sections),
grades: Object.assign(grades, grades),
domains: Object.assign(domains, domains),
competencies: Object.assign(competencies, competencies),
practice: Object.assign(practice, practice),
syllabus: Object.assign(syllabus, syllabus),
thesisReview: Object.assign(thesisReview, thesisReview),
availability: Object.assign(availability, availability),
}

export default teacher