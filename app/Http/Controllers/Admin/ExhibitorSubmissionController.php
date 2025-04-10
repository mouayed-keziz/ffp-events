<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExhibitorSubmission;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ExhibitorSubmissionController extends Controller
{
    /**
     * Download the invoice for an exhibitor submission
     * 
     * @param int $record ExhibitorSubmission ID
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function downloadInvoice($record)
    {
        $exhibitorSubmission = ExhibitorSubmission::findOrFail($record);

        // Check if invoice can be downloaded
        if (!$exhibitorSubmission->canDownloadInvoice) {
            abort(403, 'Invoice cannot be downloaded for this submission.');
        }

        $pdf = Pdf::loadView('pdf.exhibitor-submission-invoice', [
            'event' => $exhibitorSubmission->eventAnnouncement,
            'exhibitor' => $exhibitorSubmission->exhibitor,
            'submission' => $exhibitorSubmission
        ]);

        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('invoice.pdf');
    }
}
