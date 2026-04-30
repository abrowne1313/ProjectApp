<?php

namespace Tests\Feature\Pupil;

use Tests\TestCase;
use App\Models\Topics;
use App\Models\Subject;
use App\Models\PupilData; // Adjust to your actual Pupil model name
use App\Models\PupilScores; // Adjust to your actual Score model name
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevisionListBoundaryAnalysis extends TestCase
{
    use RefreshDatabase;

    protected function teacher()
    {
        // Assuming user_type 3 is a standard Teacher
        return \App\Models\UserData::factory()->create(['user_type' => 3]);
    }

    /** @test */
    public function Revision_list_includes_topics_at_amber_and_less_than_amber()
    {
          $teacher = $this->teacher();
        $pupil = PupilData::factory()->create();
        $subject = Subject::factory()->create();
        
        $topic = Topics::factory()->create(['subject_id' => $subject->id]);
        \App\Models\revisionLists::factory()->create([
            'topic_id' => $topic->id,
            'content' => 'Critical revision material',
        ]);

        // Create a score exactly at lower boundary (Target 95, Score 95)
            PupilScores::factory()->create([
            'pupil_id' => $pupil->id,
            'topic_id' => $topic->id,
            'target' => 95,
            'score' => 95,
        ]);

        // 4. Act: Generate the PDF
        $response = $this->actingAs($teacher)
            ->get(route('pupil.revisionpack', ['pupil' => $pupil->id, 'subjectID' => $subject->id]));

        // 5. Assert: The topic content MUST be passed to the view/PDF
        $response->assertStatus(200);
        $response->assertSee('Critical revision material');
    }

    /** @test */
    public function algorithm_excludes_topics_at_or_above_the_amber_threshold()
    {
        $teacher = $this->teacher();
        $pupil = PupilData::factory()->create();
        $subject = Subject::factory()->create();
        $topic = Topics::factory()->create(['subject_id' => $subject->id]);
        
        \App\Models\revisionLists::factory()->create([
            'topic_id' => $topic->id,
            'content' => 'Critical revision material',
        ]);

        // Create a score exactly ON the boundary (e.g., Target 100, Score 96)
        // This is 96% (Amber). They DO NOT need the intervention PDF.
        PupilScores::factory()->create([
            'pupil_id' => $pupil->id,
            'topic_id' => $topic->id,
            'target' => 100,
            'score' => 96, 
        ]);

        $response = $this->actingAs($teacher)
            ->get(route('pupil.revisionpack', ['pupil' => $pupil->id, 'subjectID' => $subject->id]));

        // Assert: The view should NOT contain the revision material
        $response->assertStatus(200);
        $response->assertDontSee('Critical revision material');
    }

    /** @test */
    public function algorithm_handles_division_by_zero_gracefully_when_target_is_zero()
    {
        $teacher = $this->teacher();
        $pupil = PupilData::factory()->create();
        $subject = Subject::factory()->create();
        $topic = Topics::factory()->create(['subject_id' => $subject->id]);

        // Create a scenario where the target was left at 0 to test for mathematical errors
        PupilScores::factory()->create([
            'pupil_id' => $pupil->id,
            'topic_id' => $topic->id,
            'target' => 0, 
            'score' => 50,
        ]);

        // Act & Assert
        // We use withoutExceptionHandling() to ensure no 500 errors are thrown by division by zero
        $this->withoutExceptionHandling();
        
        $response = $this->actingAs($teacher)
            ->get(route('pupil.revisionpack', ['pupil' => $pupil->id, 'subjectID' => $subject->id]));

        $response->assertStatus(200);
    }
}