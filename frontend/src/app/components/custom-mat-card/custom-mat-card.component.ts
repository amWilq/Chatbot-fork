import { Component, Input, Output, EventEmitter } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-custom-mat-card',
  templateUrl: './custom-mat-card.component.html',
  styleUrls: ['./custom-mat-card.component.scss']
})
export class CustomMatCardComponent {
  @Input() placeholder!: string;
  @Input() navigateTo!: string;
  @Input() imageSrc!: string;
  @Output() newItemEvent: EventEmitter<boolean> = new EventEmitter<boolean>();
  public isClicked: boolean = false;

  constructor(private router: Router) { }

  handleArrowIconClick(navigateTo: string) {
    this.router.navigate(['/tabs/' + navigateTo]);
  }

  handleCardClick() {
    this.isClicked = !this.isClicked;
    this.newItemEvent.emit(this.isClicked);
  }
}
