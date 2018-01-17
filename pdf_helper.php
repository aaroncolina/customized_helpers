<?php 
    
    function create_pdf_for_rfq($rid){
        /*Generate PDF*/
        $CI =& get_instance();

        $pdf_view_data = $CI->Rfq_model->get_rfq_data($rid);
        $pdf_view_data['file_name'] = str_replace(' ', '_', str_replace('/', '-', $pdf_view_data['hwname']));
        $pdf_view_data['filename'] = $pdf_view_data['fab_assy_file_name'];
        $pdf_generated_data['rfq_filename'] = $pdf_view_data['file_name'].'.pdf';        
        $pdf_generated_data['rfq_generated_by'] = $CI->session->user_details['emp_num'];

        $pdf_settings = array("title" => "                     REQUEST FOR APPROVAL",
                             "file_name" => $pdf_generated_data['rfq_filename'],            
                             "file_path" => get_rfq_upload_path($rid, 1),
                             "paper_size" => "A4");
        $pdf_view_content = 'rfq/rfq_pdf_content';
        $pdf_url = $CI->generate_pdf($pdf_settings, $pdf_view_content, $pdf_view_data);
        /**/
        
        /* This is to update the RFQ row base on the PDF generated. */
        $CI->Rfq_model->update_data($pdf_generated_data, $pdf_view_data['rfq_data_id']);
        return is_valid($pdf_generated_data) ? $pdf_generated_data : 'error';
        /**/
    }

?>