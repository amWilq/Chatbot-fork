import { Component, Input, OnInit } from '@angular/core';
import { ToastController } from '@ionic/angular';


@Component({
  selector: 'app-stacked-card',
  templateUrl: 'stacked-card-component.html',
  styleUrls: ['stacked-card-component.scss']
})
export class StackedCardComponent implements OnInit {
  @Input() cardData!: any[];
  @Input() description!: string;
  @Input() src!: string;

  isCardVisible = true;
  selectedCardIndex: number | null = null;
  showDescription: boolean[] = [];
  show = false;
  selectedOption!: string;

  data: any[] = [];

  constructor( private toastController: ToastController) {

  }

  ngOnInit() {
    console.log(localStorage);
    this.loadMotoSchemas();
    this.showDescription = this.cardData.map(() => false);
  }

  toggleCardVisibility() {
    this.isCardVisible = !this.isCardVisible;
  }

  showNewCard(card: any, i: number) {
    this.selectedCardIndex = (this.selectedCardIndex === i) ? null : i;
    this.selectedOption = card.title;
  }

  loadMotoSchemas() {
    this.data = JSON.parse(localStorage.getItem('favorites-schema') || '[]');
    if (this.data.length === 0) {
      return;
    }
  }


  removeSchema(index: number) {
    if (index >= 0 && index < this.data.length) {
      this.data.splice(index, 1);
      localStorage.setItem('favorites-schema', JSON.stringify(this.data));
      this.presentToast('Usunięto z ulubionych!');
    }
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


