<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use Illuminate\Database\Seeder;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = [
            // Art
            [
                "category" => "English",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "3D Design: Tech + Form + Fashion",
                "description" => "This course offers innovative studio projects in 3-D design, architecture, industrial design, fashion and apparel. The relationship between form and function, the visualization of concepts, process sketches, preliminary models and patterns, and product prototyping are fundamental aspects of each studio project. Students learn three-dimensional rendering and building techniques and construct functional artworks using a variety of tools and materials including industrial sewing machines, hand-building tools, cardboard, fabric, wire, metal, found objects, plastic and wood. Collaboration in the Academy's design lab offers students the unique opportunity to design and print objects using cutting-edge technologies including 3-D printers, power tools and laser cutters. Process, creativity and growth are emphasized throughout the term. Projects include shoe modeling, architectural 3-D font design, and bag and apparel design. The term concludes with a final critique of student work and process sketchbooks. The rich variety of materials and creative, challenging projects offered in this course give students the opportunity to create unique and thoughtfully designed art objects. In lieu of textbooks, there is a materials fee of $180."
            ],
            [
                "category" => "English",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Adv Photography: Beyond the Camera",
                "description" => "Advanced Photography is designed to challenge students to go beyond technical skills and photographic principles to establish a personal photographic style and artistic voice. Through hands-on practice, in-depth critique and weekly assignments, students will develop a refined, concept-driven, professional online portfolio. In-studio learning exercises will continue to challenge students to build their digital camera skills, while out-of-studio assignments will become increasingly more in-depth and creatively challenging. A range of tools will be used including Photoshop, inkjet printers, and an array of studio lighting equipment. Students will produce original work, with special attention to ways in which their technical and aesthetic decisions can clarify their artistic intentions. Photoshop and iMovie are used to explore creative and experimental possibilities for enhancing and manipulating digital photos and video. The course culminates with a self-directed final project, allowing students to practice proposal writing, project development and final presentation, while pursuing work rooted in their own interests and experiences."
            ],
            [
                "category" => "English",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Advanced Ceramics: Molding Meaning",
                "description" => "This advanced course offers a combination of assigned and self-directed projects with a further investigation of working with clay. Building off of skills gained in Ceramics I, students develop a more sophisticated approach to methods and techniques that are used to create forms with clay. Projects include throwing, hand building, modeling, industrial slip casting and mold making which will foster individual style and creativity. Students will focus on process and exploration of a broad range of contemporary clay works, functional, industrial and sculptural. Examples of contemporary artists' pottery and sculpture are used as inspiration for studio assignments. Advanced Ceramics also offers the unique opportunity to study the science and chemistry behind glazing and firing."
            ],
            [
                "category" => "English",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Advanced Printmakng: Limited Editions",
                "description" => "Building on skills and concepts acquired in ART206, this advanced printmaking course provides the opportunity to pursue individual studio projects using a range of media, inks and printing surfaces. Students develop a series of prints that revolve around a chosen concept using one or more printing processes that demonstrate a level of mastery. Class critiques will enhance the production of a portfolio of prints with a strong emphasis on experimentation, technical skill, conceptual strength, and aesthetic style. Each term, the class works as a design team to create a limited-edition printed item and a final pop-up show of selected prints from the term."
            ],
            [
                "category" => "English",
                "school" => "Middle School",
                "grade" => "",
                "unit" => "1",
                "title" => "Advanced Projects in 3-D Design",
                "description" => "This course offers the opportunity to further investigate 3-D studio projects in industrial and apparel design, 3-D printing, product prototypes, and sculptural models, and then integrates these technologies and processes into a dynamic studio practice. Students pursue a self-directed intensive that explores a specific theme or topic, thoughtfully informed by in-depth critiques, professional explorations and functional design forms, and culminating in a final pop-up exhibit. In addition to the 3-D studio, through collaboration in the Academy design lab, students have the unique opportunity to design and print objects using cutting-edge technologies including 3-D printers, power tools, and laser cutters. Throughout the term, a strong emphasis is placed on process and personal vision, and students serve as peer critics, working side-by-side as part of a dynamic design team. The relationship between form and function and the visualization of concepts are fundamental. Process sketches, preliminary models and patterns, notebooks, aesthetic emphasis, creativity, and independent goal setting are vital to the strength of the final products. Students learn entrepreneurial skills and teamwork in an energetic, rigorous studio atmosphere."
            ],
            [
                "category" => "English",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Advanced Projects: Painting Portraits",
                "description" => "This 2-D studio intensive provides the exciting opportunity to pursue more-individualized works on paper, canvas?and other surface options with an emphasis on portraiture and identity. The course encourages an experimental approach to line, color and tone, as well as form and content. Students will exercise their skills through direct observation of still life, portraiture and still images, but also experiment with drawing as a means to express personal and abstract ideas. Students are encouraged to work in new and challenging ways, such as large-scale works on paper and a series of connected images that are narrative and expressive, resulting in the evolution of a personal artistic style and portfolio. Experimentation with nontraditional mediums and mark making is infused in all studio projects. The sketchbook will play a large role in documenting ideas and recording responses to relevant topics. Each term, students will explore the link between portraiture and photography, learning to take professional studio portraits in the Photo Studio as reference imagery for a painted or drawn portrait."
            ],
            [
                "category" => "PhysicalEducation",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Capstone Intensive Studio",
                "description" => "Moving beyond ART500, Capstone Intensive Studio is a unique opportunity for in-depth studio work dedicated to concept development and supported by the required investigation of working artists. This investigation will inform student's creative thinking and impact their independent studio practice. Increased individualized study provides students the opportunity to steer their work in fresh directions with serious focus. Through the purposeful research of art historical movements and contemporary artists, students will create a strong foundation for their capstone projects. This effort is supported by art readings, self-assigned prompts, concept proposals, class critiques, a studio journal, increased self-reflection, and written responses to contemporary art issues. Each student will produce a capstone project which will be documented and published in a professionally printed artist book. By working collectively on this publication, students will learn how to professionally document their artwork, craft an effective artist statement, and understand their work within the greater context of the art world. This course culminates in an exhibition of capstone projects in the Mayer Art Center. The accompanying publication serves as an exhibition-in-print and visual anthology of the class capstones."
            ],
            [
                "category" => "PhysicalEducation",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Ceramics I: Form + Function",
                "description" => "The Exeter Clay Studio introduces students to methods used to create unique sculpture and tableware. Developing their creative concepts, students will throw on the potter's wheel, hand build forms, and create a series of pieces over the course of the term, which may include objects such as plates, cups, bowls, teapots and sculpture. Drawing inspiration from contemporary ceramic artists, the class will explore a variety of techniques for surface design, glazing and firing. The teacher will offer innovative and sophisticated approaches that will provide further opportunity for experimentation. All tableware is glazed with materials safe for food, microwave and the dishwasher."
            ],
            [
                "category" => "PhysicalEducation",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Drawing and Painting",
                "description" => "In and out of the studio, students explore the symbiotic relationship between drawing and painting and the art fundamentals common to both, including design, form, space, perspective, composition and color. Students explore a variety of mark-making techniques using graphite and paint to record ideas and visual perceptions. Projects are created using both paper and canvas surfaces, and a vibrant and versatile range of materials including graphite, charcoal, pastels, water-mixable oil paints and ink. The fluid relationship of drawing into painting is at the core of the course content, and the understanding of art as a communication tool and unique language is continuously explored and demonstrated in projects focusing on topics such as the Exeter campus, studio objects, film stills, design and related sketchbook work. Students learn a variety of contemporary drawing techniques on different surfaces, construct their own canvas surfaces and synthesize important concepts that connect drawing to painting. Studio projects place a strong emphasis on process, such as the usefulness of sketches, compositional studies and underpaintings. Dynamic sketchbook assignments will enhance the process of ongoing studio projects. The viewing of relevant works of art provides drawing students with the opportunity to make stylistic connections to significant artists and enrich their own works. Students serve as peer critics, practice collaboration and provide useful critical analysis. The course provides students with the opportunity to be fluent in both studio mediums, demonstrated in a substantial and vibrant portfolio."
            ],
            [
                "category" => "PhysicalEducation",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Photography I: Composing Concepts",
                "description" => "This course allows students to channel their excitement and passion for photography into a more intentional and sophisticated image-making process. Using digital cameras, students will gain a highly functional understanding of essential camera skills and photographic principles and learn to maintain proper exposure, focus, and creative control over the camera. Students will acquire skills in the digital studio including digital work flow management; online portfolio maintenance; Photoshop techniques and inkjet printing methods. Students will also develop their critique skills, learn to frame and present their work in a gallery, and practice writing artist statements. Each exploration challenges students to think conceptually, to shoot creatively, to develop an eye for strong composition and quality of light, and make images that start conversations. Throughout the term, student photographers develop a vibrant online portfolio based on a series of thematic photo explorations including portraiture, abstract, minimalism, studio lighting and fashion. All other materials are supplied via the studio fee."
            ],
            [
                "category" => "PhysicalEducation",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Portfolio Intensive",
                "description" => "This course provides experienced students a rich opportunity to pursue the successful completion of a professional portfolio of artwork featured in an end-of-term thesis exhibition in the Mayer Art Center, team-curated by the class with a contemporary flavor. The meaningful study of 21st-century visual culture is infused in the course through visiting artists and the investigation of artists relevant to ongoing studio work in all mediums. Students focus on photography, printmaking, painting, drawing, ceramics and 3-D design. This multimedia studio course requires strong self-direction, a unique studio investment and creative motivation. Students focus on a particular art medium and create multiple works that explore a concept or idea. Under the guidance of the instructor, students will set qualitative and quantitative goals for the term in their chosen studio concentration. Weekly process critiques are an integral?part of the course and support ongoing artistic growth. In addition, the instructor meets individually with students for more-specific feedback and to mentor the process. Useful feedback is given to students from other Art Department faculty who specialize in their chosen studio discipline to help them develop ideas and offer suggestions. Students may also receive guidance in the development of an art portfolio suitable for college admission criteria."
            ],

            // WorldLanguages
            [
                "category" => "WorldLanguages",
                "school" => "Middle School",
                "grade" => "-",
                "unit" => "1",
                "title" => "Printmaking I: Pop Art + Culture",
                "description" => "The printmaking course is a comprehensive studio experience that emphasizes experimentation and creativity while providing a strong technical basis. Students explore a variety of print processes, including screen printing, block carving, and monotype and letterpress printing. Using surfaces such as linoleum, woodblocks and silk screens, combined with a wide variety of carving tools and inks, students will create a substantial print portfolio that explores such concepts as image reversal, multiplicity, color theory, commercial applications and graphic design. Inspiration for projects includes fonts, portraits, still-life objects, photographs, media references and works by artists of the past and present. Inventive approaches, including T-shirt printing, will also be explored. Film clips and the examination of contemporary printmakers will enrich studio work."
            ],
            [
                "category" => "WorldLanguages",
                "school" => "Middle School",
                "title" => "9th-Grade WorldLanguages Program",
                "description" => "Junior WorldLanguages, sometimes referred to as the 'Prep Program,' is a specifically designed physical education offering that is required of all juniors (ninth graders). It meets four days per week each of the three terms of the school year. Exceptions to this requirement exist for ninth graders who make varsity or junior varsity interscholastic sports, earn a place in a dance class, or are selected for a part in the main stage drama production. When signing up for courses in April, all entering ninth graders must sign up for the Junior WorldLanguages Program. The goal of the Junior WorldLanguages Program is to provide a supportive learning environment for our four-year students through the structure of physical education and sport. Each of the three terms is organized slightly differently. All three terms incorporate group fitness activities, team building, socialization and fun, with the intent that each student will accomplish a comfortable transition to the Exeter community while having the opportunity to build strong bonds with instructors and classmates alike. The fall term allows students to choose three activities from an extensive list usually including such sports as: crew, volleyball, cross-country running, cycling, field hockey, tennis, weight lifting, water polo, flag football, soccer and fencing. The winter is presented as a sampling of sports including skating, squash, swimming and diving, track and field, wrestling, basketball, and fitness. The final term, spring, allows the ninth grader to transition to Exeter's custom of choosing one sport for the bulk of the spring. Tennis, diamond sports, lacrosse, track and field, cycling, crew, badminton, street games and lifetime sports, as well as ultimate Frisbee are some of the choices.",
                "grade" => "-",
                "unit" => "1",
            ],

        ];

        foreach($courses as $course){
            $category = Category::where("title", $course["category"])->first();

            $category->courses()->create([
                "school" => $course["school"],
                "title" => $course["title"],
                "description" => $course["description"],
                "unit" => $course["unit"],
            ]);
        }
    }
}
