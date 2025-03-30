<p class="mt-4 text-sm text-gray-600">
    <span class="font-semibold">Request status:</span>
    @switch($documentRequest->status)
        @case(DocumentRequest::PENDING)
            <span class="text-yellow-500">Pending</span>
            @break
        @case(DocumentRequest::PAID)
            <span class="text-green-500">Paid</span>
            @break
        @case(DocumentRequest::REJECTED)
            <span class="text-red-500">Rejected</span>
            @break
        @case(DocumentRequest::APPROVED)
            <span class="text-green-500">Approved</span>
            @break
        @case(DocumentRequest::DECLINED)
            <span class="text-red-500">Declined</span>
            @break
        @default
            <span class="text-gray-500">Unknown</span>
    @endswitch
</p>
