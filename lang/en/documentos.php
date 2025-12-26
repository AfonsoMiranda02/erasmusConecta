<?php

return [
    'title' => 'Documents',
    'subtitle' => 'Submit and manage your mobility documents',
    
    'submit' => [
        'title' => 'Submit New Document',
        'document_type' => 'Document Type',
        'document_type_placeholder' => 'Ex: Passport, Visa, Health Insurance, Mobility Contract...',
        'file' => 'File',
        'file_hint' => 'Accepted formats: PDF, DOC, DOCX, JPG, JPEG, PNG. Maximum 10MB.',
        'submit_button' => 'Submit Document',
    ],
    
    'list' => [
        'title' => 'Submitted Documents',
        'empty' => 'You have not submitted any documents yet.',
        'file' => 'File',
        'submitted_on' => 'Submitted on',
    ],
    
    'status' => [
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
    ],
    
    'rejection' => [
        'reason' => 'Rejection Reason:',
    ],
    
    'actions' => [
        'resubmit' => 'Resubmit',
    ],
    
    'messages' => [
        'submitted_success' => 'Document submitted successfully! Awaiting administrator approval.',
        'resubmitted_success' => 'Document resubmitted successfully! Awaiting new administrator approval.',
    ],
];

