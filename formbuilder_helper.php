<?php

function build($page_dir, $data){
	$CI =& get_instance();

	$data['header'] = $CI->load->view('template/header.php', $data, true);
	$data['data_parsed_to_js'] = $CI->load->view('template/data_parsed_to_js.php', $data, true);
	$data['top_navbar'] = $CI->load->view('template/top_navbar.php', $data, true);
	$data['side_navbar'] = $CI->load->view('template/side_navbar.php', $data, true);
	$data['modal'] = $CI->load->view('template/modal.php', $data, true);
	$data['footer'] = $CI->load->view('template/footer.php', $data, true);
	$data['js'] = $CI->load->view('template/js.php', $data, true);
	$data['view_page'] = $CI->load->view($page_dir .'.php', $data, true);

	$CI->load->view('template/main.php', $data);
}

function build_questions_header($questions_data){
	$CI =& get_instance();
	if(validate_array($questions_data)){	
		$data['header'] = $questions_data[0]['item_group'];
		return $CI->load->view('questions/questions_header.php', $data, true);
	}
}

function build_questions_form($questions_data, $data='', $request_data=array()){
	if($data['checklist_answers']=="error"){
		$data='';
	}
	$CI =& get_instance();
	$data['element_html'] = '';
	if(validate_array($questions_data)){
		foreach($questions_data as $key => $value){

			$value['additional_info'] = build_questions_additional_info($value['item_id'], $request_data);
			$value['elements'] = build_question_elements($value, $data['checklist_answers']);
			$data['element_html'] .= $CI->load->view('questions/questions_form.php', $value, true);
		}
	}
	$result = $CI->load->view('questions/questions_main_form.php', $data, true);
	return (is_valid($result)? $result : 'error');
}

function build_questions_validation($questions_data){
	$CI =& get_instance();
	$result = array();
	if(validate_array($questions_data)){
		foreach($questions_data as $key => $value){
			$result[] = '"questions['.$value['item_group_id'].']['.$value['item_id']. ']" : "required"';
		}
	}
	return (validate_array($result)? $result : 'error');
}

function build_questions_additional_info($item_id, $request_data){
	$CI =& get_instance();
	$result = '';
	$data = array();
	switch($item_id){
		# Total Revised Man-Hrs based on Kick-Off information (Enter the TOTAL PCB Design Man-Hrs Est for the ENTIRE project)
		case 716:
			$data['label'] = 'Submission PCB Design Man-Hrs Est.';
			$data['value'] = ((isset($request_data['estimate_layout_hrs']) || isset($request_data['adjustment_layout_hrs']))? ($request_data['estimate_layout_hrs']+$request_data['adjustment_layout_hrs']) : 0);
			break;
		# Revised Est Commit Date For Final Requester Review 
		case 717:
			$data['label'] = 'Submission Est. Commit Date';
			$data['value'] = ((is_valid($request_data['gerber_required_date']))? $request_data['gerber_required_date'] : 'N/A');
			break;
	}
	if(is_valid($data['label']) && is_valid($data['value'])){
		$result = $CI->load->view('questions/questions_additional_info.php', $data, true);
	}
	return $result;
}

function build_question_elements($question_data, $data=''){
	$CI =& get_instance();
	$result = array();
	/* INPUT ELEMENTS */
	$input_array = explode('_', $question_data['input_type']);
	switch($input_array[0]){
		case 'radio':
			$radio_list = fetch_filter($question_data['filter_name']);
			foreach($radio_list as $key => $value){
				$question_data['is_checked'] = false;
				if(is_valid($data[$question_data['item_id']]) && $data[$question_data['item_id']]==$value){
					$question_data['is_checked'] = true;
				}

				$question_data['input_value'] = $value;
				$result['input'] .= $CI->load->view('questions/questions_input_radio.php', $question_data, true);
			}
			break;
		case 'text':
			$question_data['input_value'] = '';
			if(is_valid($data[$question_data['item_id']])){
				$question_data['input_value'] = $data[$question_data['item_id']];
			}
			$question_data['size'] = ((is_valid($input_array[1]))? $input_array[1] : 'long');
			// $question_data['numeric'] = ((is_valid($input_array[2]))? $input_array[2] . ' text-center' : 'text-center');
			$result['input'] = $CI->load->view('questions/questions_input_text.php', $question_data, true);
			break;
		case 'numeric':
			$question_data['input_value'] = '';
			if(is_valid($data[$question_data['item_id']])){
				$question_data['input_value'] = $data[$question_data['item_id']];
			}
			$question_data['size'] = ((is_valid($input_array[1]))? $input_array[1] : 'long');
			$question_data['range'] = ((is_valid($input_array[2]))? $input_array[2] : '');
			$question_data['range'] = explode('-', $question_data['range']);
			// prearr($question_data);
			$result['input'] = $CI->load->view('questions/questions_input_numeric.php', $question_data, true);
			break;
		case 'textarea':
			$question_data['input_value'] = '';
			if(is_valid($data[$question_data['item_id']])){
				$question_data['input_value'] = $data[$question_data['item_id']];
			}
			$result['input'] = $CI->load->view('questions/questions_input_textarea.php', $question_data, true);
			break;
		case 'date':
			$question_data['input_value'] = '';
			if(is_valid($data[$question_data['item_id']])){
				$question_data['input_value'] = $data[$question_data['item_id']];
			}
			$question_data['size'] = ((is_valid($input_array[1]))? $input_array[1] : 'short');
			$result['input'] = $CI->load->view('questions/questions_input_date.php', $question_data, true);
			break;
	}
	/**/

	/* LINK ELEMENTS*/
	if(is_valid($question_data['link_label']) && is_valid($question_data['link'])){
		$question_data['href'] = '';
		$question_data['onClick'] = '';
		switch($question_data['link_type']){
			case 'anchor':
				$question_data['href'] = 'href="' . $question_data['link'] . '"';
				break;
			case 'popup':
				$question_data['href'] = 'href="javascript:void(0)"';
				$question_data['onClick'] = 'onClick="'. $question_data['link'] .'"';
				break;

		}

		$result['link'] = $CI->load->view('questions/questions_form_link.php', $question_data, true);
	}
	/**/


	// prearr($result);die;
	return (validate_array($result));
}


function build_email_template($template_name, $data){
	$CI =& get_instance();
    $data['detail_table'] = $CI->load->view('email/email_detail_table.php', $data, true);
    $data['inbox_link'] = $CI->load->view('email/email_inbox_link.php', $data, true);
    $data['requestor_default_header'] = $CI->load->view('email/email_requestor_default_header.php', $data, true);
    $data['requestor_default_message'] = $CI->load->view('email/email_requestor_default_message.php', $data, true);
	$data['template_view'] = $CI->load->view('email/substage_template/'.$template_name.'.php', $data, true);
	return $CI->load->view('email/substage_template/main_template.php', $data, true);
}

function link_element_builder($label, $link, $color='white'){
	return "<a class='".$color."-link' href='".$link."' target='_new'>".$label."</a>";
}

?>