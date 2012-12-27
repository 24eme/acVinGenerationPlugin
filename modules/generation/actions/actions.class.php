<?php
class generationActions extends sfActions {
    
  private function getGenerationFromRequest(sfWebRequest $request) {
    $this->type = $request['type_document'];
    $this->identifiant = isset($request['identifiant'])? $request['identifiant'] : null;
    $this->nom = ($this->identifiant)? EtablissementClient::getInstance()->retrieveById($this->identifiant)->nom : null;
    $this->date_emission = $request['date_emission'];
    $this->generation = GenerationClient::getInstance()->find(GenerationClient::getInstance()->getId($this->type, $this->date_emission));
    $this->forward404Unless($this->generation);
    return $this->generation;
  }
  
  public function executeView(sfWebRequest $request) {
    $this->generation = $this->getGenerationFromRequest($request);
  }
  
  public function executeList(sfWebRequest $request) {
      $this->type = $request['type_document'];
      $this->historyGeneration = GenerationClient::getInstance()->findHistoryWithType($this->type);
  }

  public function executeDelete(sfWebRequest $request) {
    $this->generation = $this->getGenerationFromRequest($request);
    if ($request->isMethod(sfWebRequest::POST)) {
      if ($request->getParameter('delete')) {
	$this->generation->delete();
      }
      return $this->redirect('generation_list', array('type_document' => $this->type));
    }
  }
    
}
