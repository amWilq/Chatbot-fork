import { ComponentFixture, TestBed } from '@angular/core/testing';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { ToastController } from '@ionic/angular';
import { StackedCardComponent } from './stacked-card-component';

describe('StackedCardComponent', () => {
  let component: StackedCardComponent;
  let fixture: ComponentFixture<StackedCardComponent>;

  beforeEach(() => {
    const toastControllerStub = () => ({
      create: () => ({ present: () => ({}) })
    });
    TestBed.configureTestingModule({
      schemas: [NO_ERRORS_SCHEMA],
      declarations: [StackedCardComponent],
      providers: [{ provide: ToastController, useFactory: toastControllerStub }]
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

  it('should toggle card visibility', () => {
    component.isCardVisible = true;
    component.toggleCardVisibility();
    expect(component.isCardVisible).toEqual(false);

    component.isCardVisible = false;
    component.toggleCardVisibility();
    expect(component.isCardVisible).toEqual(true);
  });

  it('should show a new card by setting selectedCardIndex', () => {
    component.selectedCardIndex = 0; // Set selectedCardIndex to 0
    component.showNewCard(0);
    expect(component.selectedCardIndex).toBe(0); // Expect selectedCardIndex to be null

    component.selectedCardIndex = null; // Set selectedCardIndex to null
    component.showNewCard(0);
    expect(component.selectedCardIndex).toBeNull(); // Expect selectedCardIndex to be 0
  });





  it('should remove a card and present a toast', async () => {
    component.cardData = [{}]; // Sample card data
    spyOn(localStorage, 'setItem');
    spyOn(component.toastController, 'create').and.resolveTo({
      present: () => Promise.resolve(),
    } as any); // Mock the ToastController response

    await component.removeSchema(0);

    expect(component.cardData.length).toEqual(0);
    expect(localStorage.setItem).toHaveBeenCalledWith('favorites-schema', JSON.stringify(component.cardData));
    expect(component.toastController.create).toHaveBeenCalledWith({
      message: 'Usunięto z ulubionych!',
      duration: 1500,
      position: 'bottom',
    });
  });

});
