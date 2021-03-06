<?php

class Ecomodel extends FT_Model{
     
    /**
     * @ignore
     */
    function Ecomodel(){
        parent::__construct();
 		$this->arc_config['store_name'] = "eco";
    }
       
    public function getImpactCategoryMenu() {
        $categories = $this->arc_getAllImpactCategories();
        $menu_html = '<form id="impact_form"><input name="impacts_field" type="hidden" />';
        $menus = array();
        $menus['main'] = "";
        foreach ($categories as $category){
            $menus['main'] .= '<option value="' . $category['uri'] . '">' . $category['label'] . '</option>';
            $menus[$category['label']] = "";
            $indicators = $this->arc_getImpactCategoryIndicators($category['uri']);
            foreach ($indicators as $indicator) {
                $menus[$category['label']] .= '<option value="' . $indicator['uri'] . '">' . $indicator['label'] . '</option>';
            }
        }
		unset($categories);
        foreach ($menus as $key=>$menu) {
            $show = "";
            if ($key == "main") {
                $show = " show";
            }
            $menu_html .= '<select class="hide' . $show . '" name="impacts_' . $key . '">' . $menu . '</select>';
            if ($key == "main") {
                $menu_html .= '<br />';
            }
        }
		$menu_html .= '<div style="width:360px"><input type="submit" class="button" id="unit_submit" value="save" style="margin-top:25px;width:80px;height:40px;font-size:16px;"/></div></form>';		
        return '<div class="dialog" id="impacts_dialog">' . $menu_html . '</div>';
         
    }
     
    private function arc_getAllImpactCategories() {
        $q = "select ?uri ?label where { " . 
            "?uri rdf:type eco:ImpactCategory . " . 
            "?uri rdfs:label ?label . " . 
            "}";
             
        $results = $this->executeQuery($q);
        if (count($results) != 0) {
            return $results;
        } else {
            return false;
        }
    }
 
    private function arc_getImpactCategoryIndicators($uri) {
        $q = "select ?uri ?label where { " . 
            "?uri rdf:type eco:ImpactAssessmentMethodCategoryDescription . " . 
            "?uri eco:hasImpactCategory '" . $uri . "' . " . 
            "?uri rdfs:label ?label . " . 
            "}";
 
        $results = $this->executeQuery($q);
        if (count($results) != 0) {
            return $results;
        } else {
            return false;
        }
    }

	// Adds the human readable label to the impact URI
	public function makeToolTip($uri) {
		$this->arc_config['store_name'] = "eco";
		if (strpos($uri,":") !== false) {
			$tooltips = array(
				'uri' => $uri, 
				'label' => $this->getLabel($uri),
				'l' => $this->getLabel($uri)
				);
		}else{
			$tooltips = array(
				'uri' => $uri, 
				'label' => $uri,
				'l' => $uri
				);
		} 
	return $tooltips;
	}
     
}