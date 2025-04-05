<template>
    <section ref="blogSection">
        <div class="row">
            <div class="col-12">
                <div class="iq-title-box text-center center mb-2">
                    <div class="image-icon mb-4 text-body">
                        <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 120 121" fill="none">
                            <path d="M119.263 81.796C119.263 77.4137 117.585 73.2739 114.601 70.0851V56.3228C114.601 41.7587 108.932 28.0524 98.6199 17.7214C88.2889 7.39041 74.5826 1.72142 59.9812 1.72142C29.8833 1.68412 5.37978 26.1876 5.37978 56.3042V70.0664C2.41474 73.2366 0.717773 77.3951 0.717773 81.796C0.717773 90.7844 7.63619 98.169 16.4194 98.8962C17.3145 102.085 20.205 104.435 23.6735 104.435C27.8506 104.435 31.2446 101.041 31.2446 96.8636V66.7284C31.2446 62.5513 27.8506 59.1573 23.6735 59.1573C20.7271 59.1573 18.2096 60.8543 16.9415 63.3158V56.2855C16.9415 32.5652 36.2422 13.2459 59.9625 13.2459C71.4311 13.2459 82.2469 17.7214 90.3961 25.852C98.5266 34.0198 103.002 44.817 103.002 56.2855V63.2972C101.753 60.8543 99.2166 59.1573 96.2889 59.1573C92.1117 59.1573 88.7178 62.5513 88.7178 66.7284V96.8636C88.7178 101.041 92.1117 104.435 96.2889 104.435C99.7574 104.435 102.648 102.085 103.543 98.8962C103.767 98.8776 103.99 98.8589 104.214 98.8216V100.929C104.214 106.337 99.8133 110.719 94.4241 110.719H72.5313V110.216C72.5313 107.735 70.5173 105.74 68.0371 105.74H54.7597C52.2795 105.74 50.2842 107.754 50.2842 110.216V114.803C50.2842 117.283 52.2982 119.297 54.7597 119.297H68.0371C70.5173 119.297 72.5313 117.283 72.5313 114.803V114.318H94.4241C101.809 114.318 107.813 108.313 107.813 100.929V97.9638C114.471 95.6142 119.263 89.2552 119.263 81.796ZM68.0371 115.717H54.7597C54.2749 115.717 53.8646 115.307 53.8646 114.822V110.234C53.8646 109.749 54.2562 109.339 54.7597 109.339H68.0371C68.5406 109.339 68.9322 109.731 68.9322 110.234V112.509V112.528V112.547V114.822C68.9322 115.307 68.5406 115.717 68.0371 115.717ZM4.31684 81.796C4.31684 77.6562 6.18164 73.7774 9.4264 71.1666C10.0791 70.6631 10.6572 70.2715 11.2912 69.9359C11.3471 69.9172 11.3844 69.8799 11.4404 69.8613C11.9812 69.5629 12.5406 69.3205 13.1187 69.0967C13.2306 69.0594 13.3425 69.0035 13.4544 68.9662C14.107 68.7424 14.7784 68.5559 15.4684 68.444H15.487C15.6921 68.4067 15.8973 68.3694 16.1024 68.3321H16.1397V95.2599C9.48234 94.3834 4.31684 88.6771 4.31684 81.796ZM23.6921 62.7564C25.8926 62.7564 27.6642 64.5466 27.6642 66.7284V96.8636C27.6642 99.0641 25.8739 100.836 23.6921 100.836C21.6036 100.836 19.9252 99.2319 19.7574 97.1806V97.162L19.7201 66.5792C19.8133 64.4534 21.5476 62.7564 23.6921 62.7564ZM92.9509 23.3345C84.1117 14.5326 72.4008 9.68412 59.9812 9.68412C34.2842 9.68412 13.3798 30.6072 13.3798 56.3042V65.2179C13.2865 65.2366 13.2119 65.2739 13.1187 65.3112C12.6712 65.4417 12.2422 65.5909 11.8133 65.7401C11.6082 65.8147 11.4031 65.8893 11.198 65.9825C10.7318 66.1876 10.2842 66.4114 9.83665 66.6538C9.74341 66.7098 9.63153 66.7471 9.53829 66.803C9.3518 66.9149 9.14668 67.0268 8.97884 67.12V56.3228C8.97884 28.183 31.86 5.30184 59.9812 5.30184C73.6129 5.30184 86.4427 10.6165 96.0837 20.2575C105.725 29.9172 111.021 42.7284 111.021 56.3228V67.12C110.834 67.0081 110.648 66.8962 110.461 66.803C110.331 66.7284 110.2 66.6725 110.051 66.6165C109.622 66.3927 109.193 66.1876 108.764 66.0011C108.578 65.9266 108.373 65.852 108.186 65.7774C107.776 65.6095 107.347 65.479 106.918 65.3485C106.806 65.3112 106.713 65.2739 106.601 65.2366V56.3228C106.601 43.8846 101.753 32.1736 92.9509 23.3345ZM96.3075 100.836C94.107 100.836 92.3355 99.0454 92.3355 96.8636V66.7284C92.3355 64.5279 94.1257 62.7564 96.3075 62.7564C98.4707 62.7564 100.242 64.5093 100.28 66.6538L100.261 97.1247C100.112 99.1946 98.3961 100.836 96.3075 100.836ZM103.879 95.2599V68.3321H103.916C104.177 68.3694 104.419 68.4254 104.662 68.4627C105.296 68.5746 105.93 68.7424 106.545 68.9662C106.639 69.0035 106.732 69.0408 106.844 69.0781C107.422 69.3018 108 69.5443 108.541 69.8613C108.597 69.8986 108.652 69.9172 108.727 69.9545C109.324 70.2715 109.902 70.6631 110.536 71.148C113.799 73.7587 115.683 77.6375 115.683 81.796C115.683 88.6585 110.555 94.3648 103.879 95.2599Z" fill="#6C757D"/>
                            <path d="M57.2399 81.8333C57.9299 82.6538 58.9368 83.12 59.9998 83.12C61.0627 83.12 62.0697 82.6538 62.7597 81.8333L68.1676 75.3997H79.5243C82.5079 75.3997 84.9322 72.9755 84.9322 69.9918V44.2948C84.9322 41.3111 82.5079 38.8869 79.5243 38.8869H40.4753C37.4916 38.8869 35.0674 41.3111 35.0674 44.2948V70.0104C35.0674 72.9941 37.4916 75.4184 40.4753 75.4184H51.832L57.2399 81.8333ZM38.6665 70.0104V44.2948C38.6665 43.2878 39.487 42.486 40.4753 42.486H79.5243C80.5313 42.486 81.3331 43.3065 81.3331 44.2948V70.0104C81.3331 71.0174 80.5126 71.8193 79.5243 71.8193H67.3285C66.8063 71.8193 66.3028 72.0617 65.9485 72.4533L59.9998 79.5209L54.0511 72.4533C53.7154 72.0431 53.2119 71.8193 52.6711 71.8193H40.4753C39.4683 71.8193 38.6665 70.9988 38.6665 70.0104Z" fill="#6C757D"/>
                            <path d="M45.6037 51.4743H74.3962C75.3846 51.4743 76.1864 50.6725 76.1864 49.6841C76.1864 48.6958 75.3846 47.8939 74.3962 47.8939H45.6037C44.6153 47.8939 43.8135 48.6958 43.8135 49.6841C43.8135 50.6725 44.6153 51.4743 45.6037 51.4743Z" fill="currentColor"/>
                            <path d="M45.6037 59.2132H74.3962C75.3846 59.2132 76.1864 58.4114 76.1864 57.423C76.1864 56.4347 75.3846 55.6328 74.3962 55.6328H45.6037C44.6153 55.6328 43.8135 56.4347 43.8135 57.423C43.8135 58.4114 44.6153 59.2132 45.6037 59.2132Z" fill="currentColor"/>
                            <path d="M60.7272 65.1807C60.7272 64.1923 59.9254 63.3904 58.937 63.3904H45.6037C44.6153 63.3904 43.8135 64.1923 43.8135 65.1807C43.8135 66.169 44.6153 66.9709 45.6037 66.9709H58.937C59.9254 66.9709 60.7272 66.169 60.7272 65.1807Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <h5 class="mb-1 text-secondary">No Query Yet</h5>
                    <p> To submit your problem simply press add button and explain your concern</p>
                    <a v-if="canhelpdesklist" href="javascript:void(0);" class="btn btn-primary px-5"
                    data-bs-toggle="modal" data-bs-target="#helpdeskModal">{{$t('messages.add')}}</a>
                </div>
            </div>
        </div>


    </section>

    <!-- ===================
    Review Modal
    ========================== -->
    <div class="modal fade" id="helpdeskModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="helpdeskModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title text-capitalize" id="helpdeskModalLabel">{{$t('messages.add_new')}}</h5>
                    <span class="text-primary custom-btn-close" data-bs-dismiss="modal" aria-label="Close" @click="closeModal()">
                        <svg xmlns="http://www.w3.org/2000/svg" width="40" height="41" viewBox="0 0 40 41" fill="none">
                            <rect x="12" y="11.8381" width="17" height="17" fill="white"></rect>
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M12.783 4.17017H27.233C32.883 4.17017 36.6663 8.13683 36.6663 14.0368V27.6552C36.6663 33.5385 32.883 37.5035 27.233 37.5035H12.783C7.13301 37.5035 3.33301 33.5385 3.33301 27.6552V14.0368C3.33301 8.13683 7.13301 4.17017 12.783 4.17017ZM25.0163 25.8368C25.583 25.2718 25.583 24.3552 25.0163 23.7885L22.0497 20.8218L25.0163 17.8535C25.583 17.2885 25.583 16.3552 25.0163 15.7885C24.4497 15.2202 23.533 15.2202 22.9497 15.7885L19.9997 18.7535L17.033 15.7885C16.4497 15.2202 15.533 15.2202 14.9663 15.7885C14.3997 16.3552 14.3997 17.2885 14.9663 17.8535L17.933 20.8218L14.9663 23.7718C14.3997 24.3552 14.3997 25.2718 14.9663 25.8368C15.2497 26.1202 15.633 26.2718 15.9997 26.2718C16.383 26.2718 16.7497 26.1202 17.033 25.8368L19.9997 22.8885L22.9663 25.8368C23.2497 26.1385 23.6163 26.2718 23.983 26.2718C24.3663 26.2718 24.733 26.1202 25.0163 25.8368Z"
                                fill="currentColor">
                            </path>
                        </svg>
                    </span>
                </div>
                <div class="modal-body">
                   <form @submit.prevent="formSubmit">
                        <input type="hidden" name="_token" :value="csrfToken">
                        <div class="mb-4">
                            <label class="form-label text-capitalize">{{$t('messages.subject')}} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" v-model="subject" placeholder="subject" id="subject" name="subject" @input="clearError('subject')">
                            <span v-if="errorMessages['subject']">
                                    <ul class="text-danger">
                                        <li v-for="err in errorMessages['subject']" :key="err">{{ err }}</li>
                                    </ul>
                                    </span>
                                <span class="text-danger">{{ errors.subject }}</span>
                                <div class="error-message" style="color: red;margin-top: 5px;">{{ subjectError }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-capitalize">{{$t('messages.description')}} <span class="text-danger">*</span></label>
                            <textarea class="form-control" v-model="description" id="description" name="description" rows="4" placeholder="Write Here..." @input="clearError('description')"></textarea>
                            <span v-if="errorMessages['description']">
                                    <ul class="text-danger">
                                        <li v-for="err in errorMessages['description']" :key="err">{{ err }}</li>
                                    </ul>
                                    </span>
                                <span class="text-danger">{{ errors.description }}</span>
                                <div class="error-message" style="color: red;margin-top: 5px;">{{ descriptionError }}</div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-capitalize" for="helpdesk_attachment">{{ $t('messages.image') }}</label>
                            <div class="custom-file">
                                <input type="file" class="form-control" id="helpdesk_attachment" name="helpdesk_attachment" ref="fileInput" accept=".jpeg, .jpg, .png, .gif, .pdf" @change="fileUpload"  />
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center gap-3 flex-wrap justify-content-end">
                            <button type="submit" class="btn btn-primary"><span v-if="IsLoading==1" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span><span v-else>{{$t('messages.submit')}}</span></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref,computed,onMounted} from 'vue';
import { useField, useForm } from 'vee-validate';
import { STORE_HELPDESK_API} from '../data/api';
import * as yup from 'yup';
import Swal from 'sweetalert2';
import useDataTable from '../hooks/Datatable'
import {useSection} from '../store/index'
import {useObserveSection} from '../hooks/Observer'

const props = defineProps(['employee_id','canhelpdesklist']);
console.log(props.canhelpdesklist)
const IsLoading=ref(0)
const fileInput = ref(null);
const fileUpload = async (e) => {
  let file = e.target.files[0]
  await readFile(file, (fileB64) => {
    ImageViewer.value = fileB64
  })
  helpdesk_attachment.value = file
}
const errorMessages = ref({})
const defaultData = () => {
  errorMessages.value = {}
  return {
    subject: '',
    description:'',
  }
}
const subjectError = ref('');
const descriptionError = ref('');
const validationSchema = yup.object({
    subject: yup.string().required('Subject is Required'),
    description: yup.string().required('Description is Required'),
})

const { handleSubmit, errors, resetForm } = useForm({
  validationSchema
})
const { value: subject } = useField('subject')
const { value: description } = useField('description')

const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
const formSubmit = handleSubmit(async () => {
    IsLoading.value=1
      // Trim values to remove spaces
      const trimmedSubject = subject.value.trim();
    const trimmedDescription = description.value.trim();

    // Validate subject
    if (!trimmedSubject) {
        subjectError.value = 'Subject is required.';
    } else {
        subjectError.value = '';
    }
    // Validate description
    if (!trimmedDescription) {
        descriptionError.value = 'Description is required.';
    } else {
        descriptionError.value = '';
    }

    // Check if there are any validation errors
    if (subjectError.value || descriptionError.value) {
        IsLoading.value = 0; // Reset loading state
        return;
    }
    const formData = new FormData();
    formData.append('subject', subject.value);
    formData.append('description', description.value);
    formData.append('employee_id', props.employee_id);

    // Get files from the file input
    const files = fileInput.value.files;
    for (let i = 0; i < files.length; i++) {
        formData.append(`helpdesk_attachment_${i}`, files[i]); // Dynamic key for each attachment
    }
    formData.append('attachment_count', files.length); // Attach count

    try {
        const response = await fetch(STORE_HELPDESK_API, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
        });

        if (response.ok) {
            IsLoading.value=0
            const responseData = await response.json();
            Swal.fire({
                title: 'Done',
                text: responseData.message,
                icon: 'success',
                iconColor: '#5F60B9'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.reload();
                }
            });
            resetForm();
        } else {
            IsLoading.value=0
            console.error('Error saving Helpdesk:', response.statusText);
        }
    } catch (error) {
        IsLoading.value=0
        console.error('Error saving Helpdesk:', error);
    }
});



const closeModal=()=>{
    ratingval.value = 0
    componentKey.value += 1
    review.value = ''
}

onMounted(() => {
    defaultData()
})

</script>
