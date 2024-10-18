<?php

namespace App\Repositories;

use App\Interface\SubjectRepositoryInterface;
use App\Models\Subject;
use Exception;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;
//use Your Model

/**
 * Class SubjectRepository.
 */
class SubjectRepository extends BaseRepository implements SubjectRepositoryInterface
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Subject::class;
    }

    public function createOrUpdate($data, $id = null)
    {
        DB::transaction(function () use ($data, $id) {
            // Get the unique list of `cource_id` from the request
            $newCourceIds = array_unique($data['cource_id']);

            // Find all existing records for the given subject code and semester
            $existingRecords = Subject::where('sem', $data['sem'])
                ->where('subject_code', $data['subject_code'])
                ->get();

            // Track the `cource_id` that need to be retained or created/updated
            $processedCourceIds = [];
            // Update existing records or create new ones
            foreach ($newCourceIds as $cource) {
                $subject = $existingRecords->firstWhere('cource_id', $cource);

                if ($subject) {
                    $subject->update([
                        'name' => $data['name'],
                        'sem' => $data['sem'],
                        'subject_code' => $data['subject_code'],
                    ]);
                } else {
                    Subject::create([
                        'name' => $data['name'],
                        'sem' => $data['sem'],
                        'subject_code' => $data['subject_code'],
                        'cource_id' => $cource,
                    ]);
                }
                // Mark this `cource_id` as processed
                $processedCourceIds[] = $cource;
            }
            // Remove records that are not in the new list of `cource_id`
            Subject::where('sem', $data['sem'])
                ->where('subject_code', $data['subject_code'])
                ->whereNotIn('cource_id', $processedCourceIds)
                ->delete();
        });
        return true;
    }
    public function remove($id)
    {
        try {
            Subject::destroy($id);
            return true;
        }catch(Exception $e) {
            return false;
        }
    }
}
