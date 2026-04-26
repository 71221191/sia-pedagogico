<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OfficialCompetencySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Insertamos los 4 Dominios oficiales
        $domains = [
            ['id' => 1, 'name' => 'Dominio 1: Preparación para el aprendizaje de los estudiantes'],
            ['id' => 2, 'name' => 'Dominio 2: Enseñanza para el aprendizaje de los estudiantes'],
            ['id' => 3, 'name' => 'Dominio 3: Participación en la gestión de la escuela articulada a la comunidad'],
            ['id' => 4, 'name' => 'Dominio 4: Desarrollo de la profesionalidad e identidad docente'],
        ];

        foreach ($domains as $d) {
            DB::table('domains')->updateOrInsert(['id' => $d['id']], $d);
        }

        // 2. Insertamos las 12 Competencias vinculadas a sus dominios
        $competencies = [
            ['domain_id' => 1, 'code' => 'Competencia 1', 'description' => 'Conoce y comprende las características de todos sus estudiantes y sus contextos, los contenidos disciplinares que enseña, los enfoques y procesos pedagógicos, con el propósito de promover capacidades de alto nivel y su formación integral.'],
            ['domain_id' => 1, 'code' => 'Competencia 2', 'description' => 'Planifica la enseñanza de forma colegiada, lo que garantiza la coherencia entre los aprendizajes que quiere lograr en sus estudiantes, el proceso pedagógico, el uso de los recursos disponibles y la evaluación en una programación curricular en permanente revisión.'],
            ['domain_id' => 2, 'code' => 'Competencia 3', 'description' => 'Crea un clima propicio para el aprendizaje, la convivencia democrática y la vivencia de la diversidad en todas sus expresiones con miras a formar ciudadanos críticos e interculturales.'],
            ['domain_id' => 2, 'code' => 'Competencia 4', 'description' => 'Conduce el proceso de enseñanza con dominio de los contenidos disciplinares y el uso de estrategias y recursos pertinentes para que todos los estudiantes aprendan de manera reflexiva y crítica lo que concierne a la solución de problemas relacionados con sus experiencias, intereses y contextos culturales.'],
            ['domain_id' => 2, 'code' => 'Competencia 5', 'description' => 'Evalúa permanentemente el aprendizaje de acuerdo con los objetivos institucionales previstos para tomar decisiones y retroalimentar a sus estudiantes y a la comunidad educativa, teniendo en cuenta las diferencias individuales y los contextos culturales.'],
            ['domain_id' => 3, 'code' => 'Competencia 6', 'description' => 'Participa en la gestión de la unidad operativa, de manera democrática, colaborativa y con un enfoque de derechos, para contribuir a la construcción y mejora continua del Proyecto Educativo Institucional y así generar aprendizajes de calidad.'],
            ['domain_id' => 3, 'code' => 'Competencia 7', 'description' => 'Establece relaciones de respeto, colaboración y corresponsabilidad con las familias, la comunidad y otras instituciones del Estado y la sociedad civil, aprovecha sus saberes y recursos en los procesos educativos y da cuenta de los resultados.'],
            ['domain_id' => 4, 'code' => 'Competencia 8', 'description' => 'Reflexiona sobre su práctica y experiencia institucional y desarrolla procesos de aprendizaje continuo de modo individual y colectivo para construir y afirmar su identidad y responsabilidad profesional.'],
            ['domain_id' => 4, 'code' => 'Competencia 9', 'description' => 'Ejerce su profesión desde una ética de respeto de los derechos fundamentales de las personas, demostrando honestidad, justicia, responsabilidad y compromiso con su función social.'],
            ['domain_id' => 4, 'code' => 'Competencia 10', 'description' => 'Gestiona su propio desarrollo profesional en forma permanente, vinculando su aprendizaje con su práctica profesional para responder a los cambios y demandas de su entorno.'],
            ['domain_id' => 4, 'code' => 'Competencia 11', 'description' => 'Gestiona los entornos virtuales y los aprovecha para su desarrollo profesional y práctica pedagógica, respondiendo a las necesidades e intereses de aprendizaje de los estudiantes y los contextos socioculturales, permitiendo el desarrollo de la ciudadanía, creatividad y participación.'],
            ['domain_id' => 4, 'code' => 'Competencia 12', 'description' => 'Investiga aspectos críticos de la práctica docente y de la institución educativa utilizando diferentes enfoques y metodologías para sistematizar y producir conocimientos en educación, con el propósito de mejorar el servicio educativo y el aprendizaje de los estudiantes.'],
        ];

        foreach ($competencies as $c) {
            DB::table('competencies')->updateOrInsert(['code' => $c['code']], $c);
        }
    }
}
