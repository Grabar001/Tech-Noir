import { Application } from "@hotwired/stimulus"
import FiltreController from "./filtre_controller"

window.Stimulus = Application.start()
Stimulus.register("filtre", FiltreController)