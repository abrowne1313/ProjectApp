<?php

namespace Tests\Feature\Pupils;

use Tests\TestCase;
use App\Models\PupilData;
use App\Models\PupilScores;
use App\Models\PupilTarget;
use App\Models\Topics;
use App\Models\Schemes;
use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RevisionPackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_a_revision_pack_pdf()
    {
        $pupil = PupilData::factory()->create(['YearGroup' => 10]);

        $subject = Subject::factory()->create();
        $scheme = Schemes::factory()->create([
            'Subject_id' => $subject->id,
            'YearGroup' => 10,
        ]);
        $topic = Topics::factory()->create([
            'Scheme_id' => $scheme->id,
            'TeachingOrder' => 1,
        ]);

        PupilTarget::factory()->create([
            'Pupil_id' => $pupil->id,
            'Subject_id' => $subject->id,
            'YearGroup' => 10,
            'Target' => 6,
        ]);

        PupilScores::factory()->create([
            'Pupil_id' => $pupil->id,
            'Topic_id' => $topic->id,
            'Score' => 4, // under target → should appear in PDF
        ]);

        $this->actingAs(\App\Models\UserData::factory()->create())
            ->get(route('pupil.revisionpack', [
                'pupil' => $pupil->id,
                'subjectID' => $subject->id,
            ]))
            ->assertStatus(200)
            ->assertHeader('content-type', 'application/pdf');
    }
}
