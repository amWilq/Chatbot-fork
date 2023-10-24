import { Component, EventEmitter, Input, OnInit, Output } from '@angular/core';
import { ToastController } from '@ionic/angular';


@Component({
  selector: 'app-stacked-card',
  templateUrl: 'stacked-card-component.html',
  styleUrls: ['stacked-card-component.scss']
})
export class StackedCardComponent {
  @Input() cardData!: any[];
  @Input() description!: string;
  @Input() src!: string;
  @Output() newItemEvent: EventEmitter<any> = new EventEmitter<any>();

  isCardVisible = true;
  selectedCardIndex: number | null = null;
  selectedOption!: string;
  isClicked: boolean = false;

  constructor( private toastController: ToastController) {

  }


  toggleCardVisibility() {
    this.isCardVisible = !this.isCardVisible;
  }

  showNewCard(index: number) {
    this.selectedCardIndex = (this.selectedCardIndex === index) ? null : index;
  }

  handleCardClick(e: Event) {
    this.isClicked = !this.isClicked;
    this.newItemEvent.emit((this.isClicked, e)) ;
  }

  removeSchema(index: number) {
    if (index >= 0 && index < this.cardData.length) {
      this.cardData.splice(index, 1);
      localStorage.setItem('favorites-schema', JSON.stringify(this.cardData));
      this.presentToast('Usunięto z ulubionych!');
    }
  }

  test(card: any, index: number) {
    console.log('test', card, index);
  }




  async presentToast(text: string ) {
    const toast = await this.toastController.create({
      message: text,
      duration: 1500,
      position: 'bottom'
    });
    await toast.present();
  }

}


