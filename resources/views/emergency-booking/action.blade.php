<?php
$auth_user = authSession();
?>
{{ html()->form('DELETE', route('emergency-booking.destroy', $booking->id))->attribute('data--submit', 'emergency-booking' . $booking->id)->open() }}
<div class="d-flex justify-content-end align-items-center">
    @if(!$booking->trashed())
        @if($auth_user->can('booking view'))
        <a class="me-2" href="{{ route('emergency-booking.show', $booking->id) }}" title="{{ __('messages.view') }}">
            <i class="fas fa-eye text-secondary"></i>
        </a>
        @endif
        
        @if($auth_user->can('booking edit') && in_array($booking->status, ['pending']))
        <a class="me-2" href="{{ route('emergency-booking.edit', $booking->id) }}" title="{{ __('messages.edit') }}">
            <i class="fas fa-pen text-secondary"></i>
        </a>
        @endif
        
        @if($auth_user->can('booking delete') && !$booking->trashed())
        <a class="me-2" href="{{ route('emergency-booking.destroy', $booking->id) }}" data--submit="emergency-booking{{$booking->id}}" 
            data--confirmation='true'
            data--ajax="true"
            data-datatable="reload"
            data-title="{{ __('messages.delete_form_title',['form'=>  __('messages.booking') ]) }}"
            title="{{ __('messages.delete_form_title',['form'=>  __('messages.booking') ]) }}"
            data-message='{{ __("messages.delete_msg") }}'>
            <i class="far fa-trash-alt text-danger"></i>
        </a>
        @endif
    @endif
    
    @if(auth()->user()->hasAnyRole(['admin']) && $booking->trashed())
        <a class="me-2" href="{{ route('emergency-booking.action',['id' => $booking->id, 'type' => 'restore']) }}"
            title="{{ __('messages.restore_form_title',['form' => __('messages.booking') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.restore_form_title',['form'=>  __('messages.booking') ]) }}"
            data-message='{{ __("messages.restore_msg") }}'
            data-datatable="reload">
            <i class="fas fa-redo text-secondary"></i>
        </a>
        <a href="{{ route('emergency-booking.action',['id' => $booking->id, 'type' => 'forcedelete']) }}"
            title="{{ __('messages.forcedelete_form_title',['form' => __('messages.booking') ]) }}"
            data--submit="confirm_form"
            data--confirmation='true'
            data--ajax='true'
            data-title="{{ __('messages.forcedelete_form_title',['form'=>  __('messages.booking') ]) }}"
            data-message='{{ __("messages.forcedelete_msg") }}'
            data-datatable="reload">
            <i class="fas fa-trash text-danger"></i>
        </a>
    @endif
</div>
{{ html()->form()->close() }}