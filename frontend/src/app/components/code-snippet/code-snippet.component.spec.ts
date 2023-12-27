import { ComponentFixture, TestBed } from '@angular/core/testing';
import { NO_ERRORS_SCHEMA } from '@angular/core';
import { Router } from '@angular/router';
import { CustomMatCardComponent } from '../custom-mat-card/custom-mat-card.component';

describe('CustomMatCardComponent', () => {
  let component: CustomMatCardComponent;
  let fixture: ComponentFixture<CustomMatCardComponent>;

  beforeEach(() => {
    const routerStub = { navigate: () => {} };
    TestBed.configureTestingModule({
      schemas: [NO_ERRORS_SCHEMA],
      declarations: [CustomMatCardComponent],
      providers: [{ provide: Router, useValue: routerStub }]
    });
    fixture = TestBed.createComponent(CustomMatCardComponent);
    component = fixture.componentInstance;
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });

  it('should have a default value of isClicked as false', () => {
    expect(component.isClicked).toEqual(false);
  });

  it('should set isClicked to true when handleCardClick is called', () => {
    component.handleCardClick();
    expect(component.isClicked).toEqual(true);
  });

  it('should toggle isClicked when handleCardClick is called twice', () => {
    component.handleCardClick(); // Set isClicked to true
    component.handleCardClick(); // Toggle isClicked to false
    expect(component.isClicked).toEqual(false);
  });

  it('should navigate to the specified route when handleArrowIconClick is called', () => {
    const routerSpy = spyOn(TestBed.inject(Router), 'navigate');
    const navigateTo = 'some-route';
    component.handleArrowIconClick(navigateTo);
    expect(routerSpy).toHaveBeenCalledWith(['/tabs/' + navigateTo]);
  });

  it('should emit newItemEvent when handleCardClick is called', () => {
    const emitSpy = spyOn(component.newItemEvent, 'emit');
    component.handleCardClick();
    expect(emitSpy).toHaveBeenCalledWith(component.selectedCard);
  });

  it('should check if the card is selected using isCardSelected', () => {
    component.selectedCard = component.placeholder; // Set selectedCard to placeholder
    expect(component.isCardSelected()).toBeTruthy();
  });
});
