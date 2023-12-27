import { ComponentFixture, TestBed } from '@angular/core/testing';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { StackedCardComponent } from '../stacked-card/stacked-card-component';
import { ToastController } from '@ionic/angular';

describe('StackedCardComponent', () => {
  let component: StackedCardComponent;
  let fixture: ComponentFixture<StackedCardComponent>;

  beforeEach(() => {
    const toastControllerStub = {
      create: () => ({
        present: () => {}
      })
    };

    TestBed.configureTestingModule({
      schemas: [NO_ERRORS_SCHEMA],
      declarations: [StackedCardComponent],
      providers: [{ provide: ToastController, useValue: toastControllerStub }]
    });
    fixture = TestBed.createComponent(StackedCardComponent);
    component = fixture.componentInstance;
  });

  it('can load instance', () => {
    expect(component).toBeTruthy();
  });

  it(`isCardVisible has default value`, () => {
    expect(component.isCardVisible).toEqual(true);
  });

  it(`isClicked has default value`, () => {
    expect(component.isClicked).toEqual(false);
  });

  it('should toggle card visibility when toggleCardVisibility is called', () => {
    component.isCardVisible = true;
    component.toggleCardVisibility();
    expect(component.isCardVisible).toBeFalse();
    component.toggleCardVisibility();
    expect(component.isCardVisible).toBeTrue();
  });

  it('should show new card when showNewCard is called', () => {
    component.selectedCardIndex = null;
    component.showNewCard(0);
    expect(component.selectedCardIndex).toBeNull;
    component.showNewCard(0);
    expect(component.selectedCardIndex).toBeNull();
  });

  it('should remove schema and present a toast when removeSchema is called', () => {
    const cardData = [{}, {}];
    component.cardData = cardData;
    spyOn(localStorage, 'setItem');
    spyOn(component.toastController, 'create').and.callThrough();

    component.removeSchema(1);

    expect(localStorage.setItem).toHaveBeenCalledWith('favorites-schema', JSON.stringify([{}]));
    expect(component.toastController.create).toHaveBeenCalledWith({
      message: 'Usunięto z ulubionych!',
      duration: 1500,
      position: 'bottom'
    });
  });
});
